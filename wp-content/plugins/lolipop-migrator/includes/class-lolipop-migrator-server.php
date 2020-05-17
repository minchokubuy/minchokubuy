<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Server class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Server Information
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Server {
	/**
	 * サーバー情報を取得
	 *
	 * @return array
	 */
	public static function all() {
		return array(
			'os'                     => static::os(),
			'memory_limit'           => static::memory_limit(),
			'archive_type'           => static::archive_type(),
			'php_version'            => static::php_version(),
			'mysql_version'          => static::db_version(),
			'wordpress_version'      => static::wordpress_version(),
			'wordpress_table_prefix' => static::wordpress_table_prefix(),
			'wordpress_db_size'      => static::wordpress_db_size(),
		);
	}

	/**
	 * OS情報を返却
	 *
	 * @return string
	 */
	public static function os() {
		return PHP_OS;
	}

	/**
	 * php.ini で設定されているメモリ上限を返却
	 *
	 * @return string
	 */
	public static function memory_limit() {
		return ini_get( 'memory_limit' );
	}

	/**
	 * 圧縮タイプを返却
	 *
	 * @return string|null
	 */
	public static function archive_type() {
		if ( static::is_disable( 'exec' ) ) {
			if ( static::has_zip_extension() ) {
				return 'zip-extension';
			}
		} else {
			$is_bsd = strpos( static::os(), 'BSD' ) > 0;

			if ( false !== static::which_zip_command() ) {
				return 'zip-command';
			}

			if ( static::has_zip_extension() ) {
				return 'zip-extension';
			}

			if ( false === $is_bsd && false !== static::which_tar_command() ) {
				return 'tar-command';
			}
		}

		return null;
	}

	/**
	 * 指定した関数が無効になっているか？
	 *
	 * @param string $func 関数名
	 * @return bool
	 */
	public static function is_disable( $func ) {
		$funcs = str_replace( ' ', '', ini_get( 'disable_functions' ) );
		if ( empty( $funcs ) ) {
			return false;
		}

		return in_array( $func, explode( ',', $funcs ), true );
	}

	/**
	 * zipモジュールが読み込まれているかどうかを返却
	 *
	 * @return bool
	 */
	public static function has_zip_extension() {
		return extension_loaded( 'zip' );
	}

	/**
	 * zip コマンドのパスを返却
	 *
	 * コマンドがない場合は false を返却
	 *
	 * @return string|bool
	 */
	public static function which_zip_command() {
		exec( 'which zip', $output, $return_var );
		return 0 === $return_var ? $output[0] : false;
	}

	/**
	 * unzip コマンドのパスを返却
	 *
	 * コマンドがない場合は false を返却
	 *
	 * @return string|bool
	 */
	public static function which_unzip_command() {
		exec( 'which unzip', $output, $return_var );
		return 0 === $return_var ? $output[0] : false;
	}

	/**
	 * tar コマンドのパスを返却
	 *
	 * コマンドがない場合は false を返却
	 *
	 * @return string|bool
	 */
	public static function which_tar_command() {
		exec( 'which tar', $output, $return_var );
		return 0 === $return_var ? $output[0] : false;
	}

	/**
	 * mysqldump コマンドのパスを返却
	 *
	 * コマンドがない場合は false を返却
	 *
	 * @return string|bool
	 */
	public static function which_mysqldump_command() {
		exec( 'which mysqldump', $output, $return_var );
		return 0 === $return_var ? $output[0] : false;
	}

	/**
	 * mysql コマンドのパスを返却
	 *
	 * コマンドがない場合は false を返却
	 *
	 * @return string|bool
	 */
	public static function which_mysql_command() {
		exec( 'which mysql', $output, $return_var );
		return 0 === $return_var ? $output[0] : false;
	}

	/**
	 * PHPのバージョンを返却
	 *
	 * @return string
	 */
	public static function php_version() {
		return phpversion();
	}

	/**
	 * MySQLのバージョンを返却
	 *
	 * @return string|false
	 */
	public static function db_version() {
		$db_version = false;

		global $wpdb;
		if ( method_exists( $wpdb, 'db_version' ) ) {
			$db_version = $wpdb->db_version();
		}

		return $db_version;
	}

	/**
	 * WordPressのバージョンを返却
	 *
	 * @return string
	 */
	public static function wordpress_version() {
		global $wp_version;
		return $wp_version;
	}

	/**
	 * WordPressで利用しているテーブルのプレフィックスを返却
	 *
	 * @return string
	 */
	public static function wordpress_table_prefix() {
		global $table_prefix;
		return $table_prefix;
	}

	/**
	 * WordPressで使用しているDBのサイズを返却
	 *
	 * @return int
	 */
	public static function wordpress_db_size() {
		global $wpdb;
		$result = $wpdb->get_row( $wpdb->prepare( 'SELECT SUM(data_length+index_length) AS BYTE FROM information_schema.tables WHERE table_schema = %s GROUP BY table_schema', DB_NAME ), ARRAY_A );
		return isset( $result['BYTE'] ) ? $result['BYTE'] : 0;
	}

	/**
	 * アーカイブファイルの情報を返却
	 *
	 * @return array
	 */
	public function get_archive_file_info() {
		$fullpath = null;
		foreach ( array( 'tgz', 'zip' ) as $extension ) {
			foreach ( glob( sprintf( '%s*.%s', LOLIPOP_MIGRATOR_TMP_DIR, $extension ) ) as $file ) {
				$fullpath = $file;
				break;
			}
		}

		if ( is_null( $fullpath ) ) {
			return array(
				'error'   => 'file',
				'message' => 'アーカイブファイルが存在しない',
			);
		}

		$info = pathinfo( $fullpath );
		if ( 'tgz' === $info['extension'] ) {
			$type = 'application/x-tar';
		} else {
			$type = 'application/zip';
		}

		return array(
			'error'               => null,
			'content_type'        => $type,
			'content_length'      => @filesize( $fullpath ),
			'attachment_filename' => sprintf( 'migrate-%s.%s', date( 'Ymd-His' ), $info['extension'] ),
			'filename'            => $fullpath,
		);
	}

	/**
	 * 処理ログの情報を返却
	 *
	 * @return array
	 */
	public function get_migrate_log_info() {
		$fullpath = LOLIPOP_MIGRATOR_LOG_FILE;

		if ( ! file_exists( $fullpath ) ) {
			return array(
				'error'   => 'file',
				'message' => 'ログファイルが存在しない',
			);
		}

		$type = 'text/plain';

		return array(
			'error'               => null,
			'content_type'        => $type,
			'content_length'      => @filesize( $fullpath ),
			'attachment_filename' => sprintf( 'migrate-%s.log', date( 'Ymd-His' ) ),
			'filename'            => $fullpath,
		);
	}

	/**
	 * WordPressのサイトURLを変更する
	 *
	 * @param  array $params 使用するパラメータ
	 * @throws Lolipop_Migrator_Migrate_Exception 処理に失敗したとき
	 */
	public function update_site_url( $params ) {
		if ( is_null( $params['src_url'] ) ) {
			$message = sprintf( '引っ越し元のURLが指定されていない' );
			throw new Lolipop_Migrator_Migrate_Exception( $message );
		}

		if ( is_null( $params['dest_url'] ) ) {
			$message = sprintf( '引っ越し先のURLが指定されていない' );
			throw new Lolipop_Migrator_Migrate_Exception( $message );
		}

		if ( $params['src_url'] === $params['dest_url'] ) {
			return;
		}

		global $wpdb;
		$sql    = $wpdb->prepare( "UPDATE {$wpdb->options} SET option_value = %s WHERE option_name = 'siteurl' OR option_name = 'home'", $params['dest_url'] );
		$result = $wpdb->query( $sql );
		if ( false === $result ) {
			$message = sprintf( 'サイトURLの更新に失敗した - %s', $params['dest_url'] );
			throw new Lolipop_Migrator_Migrate_Exception( $message );
		}
	}
}

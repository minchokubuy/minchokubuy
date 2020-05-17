<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_File class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator File Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_File {
	/**
	 * テンポラリディレクトリを作成
	 *
	 * ログ出力をしたり、ダンプファイルを作成したりするディレクトリを作成する
	 *
	 * @param string $dir ディレクトリ名
	 * @return bool
	 */
	public static function create_tmp_dir( $dir ) {
		if ( ! file_exists( $dir ) ) {
			@mkdir( $dir, 0777, true );
		}

		$result = static::create_htaccess( $dir );
		if ( $result ) {
			return static::create_index_php( $dir );
		}

		return false;
	}

	/**
	 * .htaccess を作成する
	 *
	 * @param string $dir ディレクトリ名
	 * @return bool
	 */
	protected static function create_htaccess( $dir ) {
		$result = false;

		$file = sprintf( '%s/.htaccess', $dir );
		if ( file_exists( $file ) ) {
			unlink( $file );
		}

		$data = <<<EOT
<IfModule !mod_authz_core.c>
    Order allow,deny
    Deny from all
</IfModule>
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
EOT;

		return static::put_contents( $file, $data );
	}

	/**
	 * index.php を作成する
	 *
	 * @param string $dir ディレクトリ名
	 * @return bool
	 */
	protected static function create_index_php( $dir ) {
		$result = false;

		$file = sprintf( '%s/index.php', $dir );
		if ( file_exists( $file ) ) {
			unlink( $file );
		}

		$data = '<?php // Silence is golden';

		return static::put_contents( $file, $data );
	}

	/**
	 * mysql/mysqldump で使用する設定ファイルを作成し、ファイル名を返却
	 *
	 * @param  string $dir ディレクトリ名
	 * @return string|null
	 */
	public static function create_mysql_conf( $dir ) {
		$file = sprintf( '%s%s.cnf', $dir, sha1( uniqid( '', true ) ) );

		$data  = "[client]\n";
		$data .= sprintf( "user = %s\n", DB_USER );
		$data .= sprintf( "password = %s\n", DB_PASSWORD );
		$data .= sprintf( "host = %s\n", DB_HOST );

		if ( ! static::put_contents( $file, $data ) ) {
			return null;
		}

		chmod( $file, 0600 );

		return $file;
	}

	/**
	 * アーカイブするファイル名を生成し返却
	 *
	 * @param  string $dir       ディレクトリ名
	 * @param  string $extension 拡張子
	 * @return string
	 */
	public static function create_archive_file_name( $dir, $extension ) {
		return sprintf( '%slolipop-migrator-archive%s', $dir, $extension );
	}

	/**
	 * ファイルを出力する
	 *
	 * @param string $file ファイル名
	 * @param string $data 出力する内容
	 * @return bool
	 */
	public static function put_contents( $file, $data ) {
		$result = false;

		if ( WP_Filesystem() ) {
			global $wp_filesystem;
			$result = $wp_filesystem->put_contents( $file, $data, FS_CHMOD_FILE );
		}

		return $result;
	}

	/**
	 * ファイルの内容を取得して返却する
	 *
	 * @param string $file ファイル名
	 * @return string
	 */
	public static function get_contents( $file ) {
		$result = null;

		if ( WP_Filesystem() ) {
			global $wp_filesystem;
			$result = $wp_filesystem->get_contents( $file );
		}

		return $result;
	}

	/**
	 * ディレクトリ/ファイルを削除する
	 *
	 * @param string $file ディレクトリ/ファイル名
	 */
	public static function remove( $file ) {
		if ( ! file_exists( $file ) ) {
			return;
		}

		if ( is_dir( $file ) ) {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $file, FilesystemIterator::SKIP_DOTS ),
				RecursiveIteratorIterator::CHILD_FIRST
			);

			foreach ( $iterator as $item ) {
				if ( $item->isDir() ) {
					static::remove( $item->getPathname() );
				} else {
					@unlink( $item->getPathname() );
				}
			}

			return @rmdir( $file );
		}

		return @unlink( $file );
	}

	/**
	 * テンポラリディレクトリを削除する
	 */
	public static function remove_tmp_dir() {
		$result = static::remove( LOLIPOP_MIGRATOR_TMP_DIR );

		if ( ! $result ) {
			return array(
				'error'   => 'file',
				'message' => 'テンポラリディレクトリの削除に失敗した',
			);
		}

		return array(
			'error'    => null,
			'response' => array(
				'message' => 'テンポラリディレクトリを削除した',
			),
		);
	}

	/**
	 * ダンプファイル/アーカイブファイル/mysqldump設定ファイルを削除する
	 */
	public static function remove_tmp_files() {
		foreach ( array( 'sql', 'tgz', 'zip', 'cnf' ) as $extension ) {
			foreach ( glob( sprintf( '%s*.%s', LOLIPOP_MIGRATOR_TMP_DIR, $extension ) ) as $file ) {
				$result = unlink( $file );
				if ( ! $result ) {
					return array(
						'error'   => 'file',
						'message' => 'テンポラリファイルの削除に失敗した',
					);
				}
			}
		}

		return array(
			'error'    => null,
			'response' => array(
				'message' => 'テンポラリファイルを削除した',
			),
		);
	}
}

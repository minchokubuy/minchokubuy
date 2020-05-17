<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Remote_Server class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Remote Server Action
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Remote_Server {
	/**
	 * 引っ越し元サイトからバックアップファイルを取得
	 *
	 * @param  array $params 引っ越し元のURL/ID/パスワードの配列
	 * @return array ダウンロードしたアーカイブファイルの情報
	 * @throws Lolipop_Migrator_Download_Exception 処理に失敗した場合
	 */
	public function download_archive_file( $params ) {
		$this->wp_login( $params );
		$response = $this->wp_execute_backup( $params );
		return $this->wp_download_archive_file( $params, $response );
	}

	/**
	 * WordPressにログインする
	 *
	 * @param array $params 引っ越し元のURL/ID/パスワードの配列
	 * @throws Lolipop_Migrator_Download_Exception 処理に失敗した場合
	 */
	public function wp_login( $params ) {
		if ( is_null( $params['src_url'] ) || is_null( $params['id'] ) || is_null( $params['pass'] ) ) {
			$message = 'ログインするための情報が不足している';
			throw new Lolipop_Migrator_Download_Exception( $message );
		}

		Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_COOKIE_FILE );

		$url          = sprintf( '%s/wp-login.php', rtrim( $params['src_url'], '/' ) );
		$login_params = array(
			'log' => $params['id'],
			'pwd' => $params['pass'],
		);

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_POST, true );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $login_params ) );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, LOLIPOP_MIGRATOR_COOKIE_FILE );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, LOLIPOP_MIGRATOR_COOKIE_FILE );
		$response = curl_exec( $ch );
		$error    = curl_error( $ch );
		curl_close( $ch );

		if ( false === $response ) {
			$message = sprintf( '引っ越し元サイトへのログインに失敗した [error:%s]', $error );
			throw new Lolipop_Migrator_Download_Exception( $message );
		}
	}

	/**
	 * 引っ越し元でバッグアップを実行する
	 *
	 * @param  array $params 引っ越し元のURL/ID/パスワードの配列
	 * @return array
	 * @throws Lolipop_Migrator_Download_Exception 処理に失敗した場合
	 */
	public function wp_execute_backup( $params ) {
		if ( is_null( $params['src_url'] ) ) {
			$message = 'バックアップするための情報が不足している';
			throw new Lolipop_Migrator_Download_Exception( $message );
		}

		$url = sprintf( '%s/wp-admin/admin-ajax.php?action=lolipop_migrator_backup', rtrim( $params['src_url'], '/' ) );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, LOLIPOP_MIGRATOR_COOKIE_FILE );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, LOLIPOP_MIGRATOR_COOKIE_FILE );
		$response = curl_exec( $ch );
		$info     = curl_getinfo( $ch );
		$error    = curl_error( $ch );
		curl_close( $ch );

		if ( false === $response ) {
			$message = sprintf( '引っ越し元サイトでのバックアップ処理に失敗した [error:%s]', $error );
			throw new Lolipop_Migrator_Download_Exception( $message );
		}

		return json_decode( substr( $response, $info['header_size'] ), true );
	}

	/**
	 * 移設元からバックアップしたアーカイブファイルをダウンロードする
	 *
	 * @param  array $params 引っ越し元のURL/ID/パスワードの配列
	 * @param  array $backup_response 引っ越し元のURL/ID/パスワードの配列
	 * @return array ダウンロードしたアーカイブファイルの情報
	 * @throws Lolipop_Migrator_Download_Exception 処理に失敗した場合
	 */
	public function wp_download_archive_file( $params, $backup_response ) {
		if ( is_null( $params['src_url'] ) ||
			! isset( $backup_response['data'] ) ||
			! isset( $backup_response['data']['name'] ) || ! isset( $backup_response['data']['size'] ) ) {
			$message = 'ダウンロードするための情報が不足している';
			throw new Lolipop_Migrator_Download_Exception( $message );
		}

		$url = sprintf( '%s/wp-admin/admin-ajax.php?action=lolipop_migrator_download', rtrim( $params['src_url'], '/' ) );

		$archive_file = sprintf( '%s%s', LOLIPOP_MIGRATOR_TMP_DIR, basename( $backup_response['data']['name'] ) );
		$fp           = fopen( $archive_file, 'wb' );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true );
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_FILE, $fp );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, LOLIPOP_MIGRATOR_COOKIE_FILE );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, LOLIPOP_MIGRATOR_COOKIE_FILE );
		$response = curl_exec( $ch );
		$error    = curl_error( $ch );
		curl_close( $ch );

		fclose( $fp );

		$size = -1;
		if ( file_exists( $archive_file ) ) {
			$size = filesize( $archive_file );
		}

		if ( ( false === $response ) ||
			( $backup_response['data']['size'] !== $size ) ) {
			$message = sprintf( '引っ越し元サイトからのダウンロードを失敗した [error:%s]', $error );
			throw new Lolipop_Migrator_Download_Exception( $message );
		}

		return array(
			'file' => array(
				'name' => $archive_file,
				'size' => $size,
			),
		);
	}
}

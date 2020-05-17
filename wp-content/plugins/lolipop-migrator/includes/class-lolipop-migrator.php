<?php
/**
 * Lolipop Migrator: Lolipop_Migrator class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator
 *
 * @since 0.1.0
 */
class Lolipop_Migrator {
	/**
	 * プラグインロード時に呼ばれる
	 */
	public static function load() {
		Lolipop_Migrator_File::create_tmp_dir( LOLIPOP_MIGRATOR_TMP_DIR );

		$instance = new static();
		$instance->init();
	}

	/**
	 * プラグイン有効化時に作業ディレクトリ生成
	 */
	public static function activate() {
		Lolipop_Migrator_File::create_tmp_dir( LOLIPOP_MIGRATOR_TMP_DIR );
	}

	/**
	 * プラグイン無効化時に作業ディレクトリ削除
	 */
	public static function deactivate() {
		Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_TMP_DIR );
	}

	/**
	 * プラグイン初期化処理
	 */
	protected function init() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return false;
		};

		$this->add_actions();

		register_shutdown_function(
			function () {
				$error = error_get_last();

				if ( ! is_null( $error ) && strpos( $error['file'], LOLIPOP_MIGRATOR_PLUGIN_NAME ) !== false ) {
					$message = sprintf( '%s:%s', $error['line'], $error['message'] );
					Lolipop_Migrator_Response::error( 'shutdown', $message, 500, false );
				}
			}
		);
	}

	/**
	 * Ajax エントリを追加
	 */
	protected function add_actions() {
		add_action( 'wp_ajax_lolipop_migrator_server_info', array( $this, 'server_info' ) );
		add_action( 'wp_ajax_lolipop_migrator_backup', array( $this, 'backup' ) );
		add_action( 'wp_ajax_lolipop_migrator_download', array( $this, 'download' ) );
		add_action( 'wp_ajax_lolipop_migrator_download_log', array( $this, 'download_log' ) );
		add_action( 'wp_ajax_lolipop_migrator_remove_files', array( $this, 'remove_files' ) );
		add_action( 'wp_ajax_lolipop_migrator_remove_dir', array( $this, 'remove_dir' ) );
		add_action( 'wp_ajax_lolipop_migrator_migrate', array( $this, 'migrate' ) );
		add_action( 'wp_ajax_lolipop_migrator_change_site_url', array( $this, 'change_site_url' ) );
	}

	/**
	 * サーバー情報を返却
	 */
	public function server_info() {
		Lolipop_Migrator_Log::set_mode( true );

		Lolipop_Migrator_Response::success( Lolipop_Migrator_Server::all() );
	}

	/**
	 * データベースのダンプおよびコンテンツのアーカイブを行う
	 */
	public function backup() {
		Lolipop_Migrator_Log::set_mode( true );

		$instance = new Lolipop_Migrator_Backup();

		@ini_set( 'memory_limit', '1024M' );
		@set_time_limit( 0 );

		$result = $instance->backup();

		if ( ! is_null( $result['error'] ) ) {
			Lolipop_Migrator_Response::error( $result['error'], $result['message'] );
		}

		Lolipop_Migrator_Response::success( $result['response'] );
	}

	/**
	 * アーカイブしたファイルをダウンロード
	 */
	public function download() {
		Lolipop_Migrator_Log::set_mode( true );

		$instance = new Lolipop_Migrator_Server();

		@ini_set( 'memory_limit', '1024M' );
		@set_time_limit( 0 );

		$result = $instance->get_archive_file_info();

		if ( ! is_null( $result['error'] ) ) {
			Lolipop_Migrator_Response::error( $result['error'], $result['message'] );
		}

		header( sprintf( 'Content-Type: %s', $result['content_type'] ) );
		header( 'X-Content-Type-Options: nosniff' );
		header( sprintf( 'Content-Length: %s', $result['content_length'] ) );
		header( sprintf( 'Content-Disposition: attachment; filename="%s"', $result['attachment_filename'] ) );

		// output buffering が有効の場合、全部フラッシュしておく
		wp_ob_end_flush_all();
		flush();
		ob_implicit_flush();

		$fp = fopen( $result['filename'], 'rb' );
		while ( ! feof( $fp ) ) {
			echo fread( $fp, 4096 );
		}
		fclose( $fp );
		exit();
	}

	/**
	 * 処理ログをダウンロード
	 */
	public function download_log() {
		$instance = new Lolipop_Migrator_Server();

		@ini_set( 'memory_limit', '1024M' );
		@set_time_limit( 0 );

		$result = $instance->get_migrate_log_info();

		if ( ! is_null( $result['error'] ) ) {
			Lolipop_Migrator_Response::error( $result['error'], $result['message'] );
		}

		header( sprintf( 'Content-Type: %s', $result['content_type'] ) );
		header( 'X-Content-Type-Options: nosniff' );
		header( sprintf( 'Content-Length: %s', $result['content_length'] ) );
		header( sprintf( 'Content-Disposition: attachment; filename="%s"', $result['attachment_filename'] ) );

		echo Lolipop_Migrator_File::get_contents( $result['filename'] );
		exit();
	}

	/**
	 * ダンプファイル/アーカイブファイル/mysqldump設定ファイルを削除する
	 */
	public function remove_files() {
		$result = Lolipop_Migrator_File::remove_tmp_files();

		if ( ! is_null( $result['error'] ) ) {
			Lolipop_Migrator_Response::error( $result['error'], $result['message'] );
		}

		Lolipop_Migrator_Response::success( $result['response'] );
	}

	/**
	 * テンポラリディレクトリを削除する
	 */
	public function remove_dir() {
		$result = Lolipop_Migrator_File::remove_tmp_dir();

		if ( ! is_null( $result['error'] ) ) {
			Lolipop_Migrator_Response::error( $result['error'], $result['message'] );
		}

		Lolipop_Migrator_Response::success( $result['response'] );
	}

	/**
	 * 引っ越し元サイトからバックアップファイルを取得し、引っ越しを行う
	 */
	public function migrate() {
		$instance = new Lolipop_Migrator_Migrate();

		@ini_set( 'memory_limit', '1024M' );
		@set_time_limit( 0 );

		$result = $instance->migrate( $this->build_migrate_params() );

		if ( ! is_null( $result['error'] ) ) {
			Lolipop_Migrator_Response::error( $result['error'], $result['message'] );
		}

		Lolipop_Migrator_Response::success( $result['response'] );
	}

	/**
	 * 引っ越しで使用するパラメータをリクエストパラメータから作成する
	 *
	 * @return array
	 */
	protected function build_migrate_params() {
		$params = array(
			'src_url'  => null,
			'dest_url' => null,
			'id'       => null,
			'pass'     => null,
		);

		if ( isset( $_POST['lolipop_migrator_src_url'] ) ) {
			$params['src_url'] = $_POST['lolipop_migrator_src_url'];
		}

		if ( isset( $_POST['lolipop_migrator_id'] ) ) {
			$params['id'] = $_POST['lolipop_migrator_id'];
		}

		if ( isset( $_POST['lolipop_migrator_pass'] ) ) {
			$params['pass'] = $_POST['lolipop_migrator_pass'];
		}

		if ( isset( $_POST['lolipop_migrator_dest_url'] ) ) {
			$params['dest_url'] = $_POST['lolipop_migrator_dest_url'];
		}

		return $params;
	}

	/**
	 * 指定されたサイトURLに書き換える
	 */
	public function change_site_url() {
		$instance = new Lolipop_Migrator_WordPress();

		$site_url = null;
		if ( isset( $_POST['lolipop_migrator_site_url'] ) ) {
			$site_url = $_POST['lolipop_migrator_site_url'];
		}

		$result = $instance->change_site_url( $site_url );

		if ( ! is_null( $result['error'] ) ) {
			Lolipop_Migrator_Response::error( $result['error'], $result['message'] );
		}

		Lolipop_Migrator_Response::success( $result['response'] );
	}
}

<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Migrate class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Migrate Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Migrate {
	/**
	 * 引っ越し元サイトからアーカイブファイルを取得し復元する
	 *
	 * @param  array $params 使用するパラメータ
	 * @return array
	 */
	public function migrate( $params ) {
		try {
			Lolipop_Migrator_Log::info( Lolipop_Migrator_Server::all() );

			$this->validate();

			Lolipop_Migrator_File::remove_tmp_files();

			$remote_server = new Lolipop_Migrator_Remote_Server();
			$result        = $remote_server->download_archive_file( $params );

			$extract = new Lolipop_Migrator_Extract();
			$result  = $extract->extract( $result );

			$restore = new Lolipop_Migrator_Restore();
			$result  = $restore->restore();

			$server = new Lolipop_Migrator_Server();
			$server->update_site_url( $params );
		} catch ( Lolipop_Migrator_Download_Exception $e ) {
			return array(
				'error'   => 'download',
				'message' => $e->getMessage(),
			);
		} catch ( Lolipop_Migrator_Extract_Exception $e ) {
			Lolipop_Migrator_File::remove_tmp_files();

			return array(
				'error'   => 'extract',
				'message' => $e->getMessage(),
			);
		} catch ( Lolipop_Migrator_Restore_Exception $e ) {
			Lolipop_Migrator_File::remove_tmp_files();

			return array(
				'error'   => 'restore',
				'message' => $e->getMessage(),
			);
		} catch ( Lolipop_Migrator_Migrate_Exception $e ) {
			Lolipop_Migrator_File::remove_tmp_files();

			return array(
				'error'   => 'migrate',
				'message' => $e->getMessage(),
			);
		}

		Lolipop_Migrator_File::remove_tmp_files();

		return array(
			'error'    => null,
			'response' => array(
				'message' => '引っ越し処理が成功した',
			),
		);
	}

	/**
	 * PHP と WordPress のバージョンをチェックする
	 *
	 * @throws Lolipop_Migrator_Exception PHP/WordPressのバージョンが低かった場合
	 */
	public function validate() {
		if ( version_compare( Lolipop_Migrator_Server::php_version(), '5.3', '<' ) ) {
			$message = sprintf( 'このプラグインを使用するには PHP 5.3 以上の環境が必要です。(動作しているバージョン: %s)', Lolipop_Migrator_Server::php_version() );
			throw new Lolipop_Migrator_Exception( $message );
		};

		if ( version_compare( Lolipop_Migrator_Server::wordpress_version(), '4.0', '<' ) ) {
			$message = sprintf( 'このプラグインを使用するには WordPress 4.0 以上の環境が必要です。(動作しているバージョン: %s)', Lolipop_Migrator_Server::wordpress_version() );
			throw new Lolipop_Migrator_Exception( $message );
		}
	}
}

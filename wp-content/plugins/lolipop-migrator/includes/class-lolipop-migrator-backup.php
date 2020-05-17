<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Backup class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Backup Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Backup {
	/**
	 * データベースのダンプおよびコンテンツのアーカイブを行う
	 *
	 * @return array
	 */
	public function backup() {
		try {
			Lolipop_Migrator_Log::info( Lolipop_Migrator_Server::all() );

			$this->validate();

			Lolipop_Migrator_File::remove_tmp_files();

			$dump = new Lolipop_Migrator_Dump();
			$dump->dump();

			$archive = new Lolipop_Migrator_Archive();
			$result  = $archive->archive();
		} catch ( Lolipop_Migrator_Dump_Exception $e ) {
			Lolipop_Migrator_File::remove_tmp_files();

			return array(
				'error'   => 'dump',
				'message' => $e->getMessage(),
			);
		} catch ( Lolipop_Migrator_Archive_Exception $e ) {
			Lolipop_Migrator_File::remove_tmp_files();

			return array(
				'error'   => 'archive',
				'message' => $e->getMessage(),
			);
		} catch ( Lolipop_Migrator_Exception $e ) {
			return array(
				'error'   => 'validate',
				'message' => $e->getMessage(),
			);
		}

		Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_DUMP_FILE );

		return array(
			'error'    => null,
			'response' => $result,
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

<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Archive class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator File Archive Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Archive {
	/**
	 * 処理を行うインスタンス
	 *
	 * @var Lolipop_Migrator_Base_Archiver
	 */
	protected $archiver;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		if ( Lolipop_Migrator_Server::is_disable( 'exec' ) ) {
			if ( Lolipop_Migrator_Server::has_zip_extension() ) {
				$extension      = '.zip';
				$this->archiver = new Lolipop_Migrator_Zip_Extension_Archiver( $extension );
			}
		} else {
			$is_bsd = strpos( Lolipop_Migrator_Server::os(), 'BSD' ) > 0;

			if ( false !== Lolipop_Migrator_Server::which_zip_command() ) {
				$extension      = '.zip';
				$this->archiver = new Lolipop_Migrator_Zip_Command_Archiver( $extension );
			} elseif ( Lolipop_Migrator_Server::has_zip_extension() ) {
				$extension      = '.zip';
				$this->archiver = new Lolipop_Migrator_Zip_Extension_Archiver( $extension );
			} elseif ( false === $is_bsd && false !== Lolipop_Migrator_Server::which_tar_command() ) {
				$extension      = '.tgz';
				$this->archiver = new Lolipop_Migrator_Tar_Command_Archiver( $extension );
			}
		}
	}

	/**
	 * ファイルのアーカイブを行う
	 *
	 * @return array
	 * @throws Lolipop_Migrator_Archive_Exception 処理に失敗した場合
	 */
	public function archive() {
		if ( is_null( $this->archiver ) ) {
			throw new Lolipop_Migrator_Archive_Exception( '適切なアーカイバーが選択できなかった' );
		}

		$class = get_class( $this->archiver );
		Lolipop_Migrator_Log::info( sprintf( '[start] archiver:%s - ファイルのアーカイブを開始', $class ) );

		$archive_file = $this->archiver->archive();

		$file_size = @filesize( $archive_file );

		Lolipop_Migrator_Log::info( sprintf( '[end] archiver:%s - ファイルのアーカイブを終了 - file:%s - size:%s', $class, $archive_file, $file_size ) );

		return array(
			'name' => $archive_file,
			'size' => $file_size,
		);
	}
}

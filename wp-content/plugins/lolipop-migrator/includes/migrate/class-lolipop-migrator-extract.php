<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Extract class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Extract Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Extract {
	/**
	 * データベースのダンプおよびコンテンツのアーカイブファイルを展開する
	 *
	 * @param  array $archive_file_info アーカイブファイルの情報
	 * @throws Lolipop_Migrator_Extract_Exception 処理に失敗した場合
	 */
	public function extract( $archive_file_info ) {
		if ( ! isset( $archive_file_info['file']['name'] ) ) {
			throw new Lolipop_Migrator_Extract_Exception( '展開するアーカイブファイルが指定されていない' );
		}

		$archive_file = $archive_file_info['file']['name'];
		$pathinfo     = pathinfo( $archive_file );
		$extension    = $pathinfo['extension'];
		$extractor    = null;

		if ( 'tgz' === $extension ) {
			$extractor = new Lolipop_Migrator_Tar_Command_Extractor();
		} elseif ( 'zip' === $extension ) {
			$extractor = new Lolipop_Migrator_Unzip_Command_Extractor();
		}

		if ( is_null( $extractor ) ) {
			$message = sprintf( '指定されたアーカイブファイルの拡張子には対応していない [extension:%s]', $extension );
			throw new Lolipop_Migrator_Extract_Exception( $message );
		}

		$class = get_class( $extractor );
		Lolipop_Migrator_Log::info( sprintf( '[start] extractor:%s - アーカイブファイルの展開を開始', $class ) );

		$extractor->extract( $archive_file );

		Lolipop_Migrator_Log::info( sprintf( '[end] extractor:%s - アーカイブファイルの展開を終了 - file:%s', $class, $archive_file ) );
	}
}

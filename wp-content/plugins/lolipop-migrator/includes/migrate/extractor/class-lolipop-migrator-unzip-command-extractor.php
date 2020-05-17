<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Unzip_Command_Extractor class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator File Extract By unzip command
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Unzip_Command_Extractor extends Lolipop_Migrator_Base_Extractor {
	/**
	 * ファイルの展開を行う
	 *
	 * @param  string $archive_file アーカイブファイル名
	 * @throws Lolipop_Migrator_Extract_Exception 処理に失敗した場合
	 */
	public function extract( $archive_file ) {
		$class = get_class( $this );

		if ( ! file_exists( $archive_file ) ) {
			$message = sprintf( '[error] exportor:%s - アーカイブファイルが存在しない - %s', $class, $archive_file );
			throw new Lolipop_Migrator_Extract_Exception( $message );
		}

		$this->remove_wp_content();

		$command = Lolipop_Migrator_Server::which_unzip_command();

		$command = 'cd ' . dirname( WP_CONTENT_DIR ) . ' && ' . $command
			. ' -o ' . $archive_file . ' 2> ' . LOLIPOP_MIGRATOR_ARCHIVE_ERROR_LOG_FILE
			. ' > /dev/null';

		exec( $command, $output, $return_var );

		$content = Lolipop_Migrator_File::get_contents( LOLIPOP_MIGRATOR_EXTRACT_ERROR_LOG_FILE );
		Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_EXTRACT_ERROR_LOG_FILE );

		if ( 0 !== $return_var || 0 < strlen( $content ) ) {
			$message = sprintf( '[error] exportor:%s - unzip コマンドでの展開に失敗した - %s - %s', $class, $archive_file, $content );
			throw new Lolipop_Migrator_Extract_Exception( $message );
		}
	}
}

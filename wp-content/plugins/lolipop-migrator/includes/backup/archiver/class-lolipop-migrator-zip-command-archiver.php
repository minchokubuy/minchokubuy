<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Zip_Command_Archiver class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator File Archive By zip command
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Zip_Command_Archiver extends Lolipop_Migrator_Base_Archiver {
	/**
	 * ファイルのアーカイブを行う
	 *
	 * @return string
	 * @throws Lolipop_Migrator_Archive_Exception 処理に失敗した場合
	 */
	public function archive() {
		$class = get_class( $this );

		$command = Lolipop_Migrator_Server::which_zip_command();

		$exclude_arg = '';
		foreach ( $this->exclude_path_list as $path ) {
			$exclude_arg .= sprintf( ' --exclude=%s', $path );
		}

		$command = 'cd ' . dirname( WP_CONTENT_DIR ) . ' && ' . $command . ' -r' . $exclude_arg
			. ' ' . $this->archive_file . ' wp-content 2> ' . LOLIPOP_MIGRATOR_ARCHIVE_ERROR_LOG_FILE
			. ' > /dev/null';

		exec( $command, $output, $return_var );

		$content = Lolipop_Migrator_File::get_contents( LOLIPOP_MIGRATOR_ARCHIVE_ERROR_LOG_FILE );
		Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_ARCHIVE_ERROR_LOG_FILE );

		if ( 0 !== $return_var || 0 < strlen( $content ) ) {
			Lolipop_Migrator_File::remove( $this->archive_file );
			$message = sprintf( '[error] archiver:%s - zip コマンドでのアーカイブに失敗した - %s', $class, $content );
			throw new Lolipop_Migrator_Archive_Exception( $message );
		}

		return $this->archive_file;
	}
}

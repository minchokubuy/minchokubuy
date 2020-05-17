<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Zip_Extension_Archiver class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator File Archive By php zip extension
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Zip_Extension_Archiver extends Lolipop_Migrator_Base_Archiver {
	/**
	 * ファイルのアーカイブを行う
	 *
	 * @return string
	 * @throws Lolipop_Migrator_Archive_Exception 処理に失敗した場合
	 */
	public function archive() {
		$class = get_class( $this );

		$zip = new ZipArchive();
		if ( ! $zip->open( $this->archive_file, ZipArchive::CREATE ) ) {
			$message = sprintf( '[error] archiver:%s - PHP zip 拡張でのアーカイブファイルの作成に失敗した', $class );
			throw new Lolipop_Migrator_Archive_Exception( $message );
		}

		$zip->addEmptyDir( 'wp-content' );

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator( WP_CONTENT_DIR, FilesystemIterator::SKIP_DOTS ),
			RecursiveIteratorIterator::SELF_FIRST
		);

		$regexes = array();
		foreach ( $this->exclude_path_list as $path ) {
			$regexes[] = sprintf( '|%s|', str_replace( '*', '.*', $path ) );
		}

		foreach ( $iterator as $item ) {
			$fullpath = $item->getPathname();
			$path     = sprintf( 'wp-content%s', str_replace( WP_CONTENT_DIR, '', $fullpath ) );

			foreach ( $regexes as $regex ) {
				if ( 1 === preg_match( $regex, $path ) ) {
					continue;
				}
			}

			if ( $item->isDir() ) {
				$zip->addEmptyDir( $path );
				continue;
			}

			$zip->addFile( $fullpath, $path );
		}

		$result = $zip->close();

		if ( ! $result ) {
			Lolipop_Migrator_File::remove( $this->archive_file );
			$message = sprintf( '[error] archiver:%s - PHP zip 拡張でのアーカイブに失敗した', $class );
			throw new Lolipop_Migrator_Archive_Exception( $message );
		}

		return $this->archive_file;
	}
}

<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Base_Extractor class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator File Extract Base Class
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Base_Extractor {
	/**
	 * wp-content ディレクトリ以下を削除する
	 *
	 * ただし、プラグイン本体と作業ディレクトリは対象外
	 */
	public function remove_wp_content() {
		foreach ( glob( sprintf( '%s/*', WP_CONTENT_DIR ) ) as $file ) {
			if ( is_dir( $file ) ) {
				$iterator = new RecursiveIteratorIterator(
					new RecursiveDirectoryIterator( $file, FilesystemIterator::SKIP_DOTS ),
					RecursiveIteratorIterator::CHILD_FIRST
				);

				foreach ( $iterator as $item ) {
					$regex = sprintf( '/%s/', LOLIPOP_MIGRATOR_PLUGIN_NAME );
					if ( 1 === preg_match( $regex, $item->getPathname() ) ) {
						continue;
					}
					if ( $item->isDir() ) {
						Lolipop_Migrator_File::remove( $item->getPathname() );
					} else {
						unlink( $item->getPathname() );
					}
				}

				$regex = sprintf( '/(plugins|%s)$/', LOLIPOP_MIGRATOR_PLUGIN_NAME );
				if ( 1 !== preg_match( $regex, $file ) ) {
					rmdir( $file );
				}
			} else {
				unlink( $file );
			}
		}
	}
}

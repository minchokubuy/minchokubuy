<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Base_Archiver class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator File Archive Base Class
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Base_Archiver {
	/**
	 * 圧縮したファイルパス
	 *
	 * @var string
	 */
	protected $archive_file;

	/**
	 * 除外対象ファイルパス
	 *
	 * @var array
	 */
	protected $exclude_path_list;

	/**
	 * コンストラクタ
	 *
	 * @param string $extension 拡張子
	 */
	public function __construct( $extension ) {
		$this->archive_file = Lolipop_Migrator_File::create_archive_file_name( LOLIPOP_MIGRATOR_TMP_DIR, $extension );

		$this->exclude_path_list = array(
			'wp-content/cache/*',
			'wp-content/ai1wm-backups/*',
			'wp-content/updraft/*',
			'wp-content/uploads/backwpup-*',
			'wp-content/uploads/backup-guard/*',
			sprintf( 'wp-content%s', str_replace( WP_CONTENT_DIR, '', LOLIPOP_MIGRATOR_ARCHIVE_ERROR_LOG_FILE ) ),
			sprintf( 'wp-content%s', str_replace( WP_CONTENT_DIR, '', $this->archive_file ) ),
		);
	}
}

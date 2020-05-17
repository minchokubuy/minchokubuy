<?php
/**
 * Lolipop Migrator
 *
 * @package Lolipop_Migrator
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * プラグイン直下の lolipop-migrator.php で書くと読みづらくなるのでこちらにまとめる
 */
define( 'LOLIPOP_MIGRATOR_PLUGIN_NAME', basename( LOLIPOP_MIGRATOR_PLUGIN_DIR ) );
define( 'LOLIPOP_MIGRATOR_TMP_DIR', WP_CONTENT_DIR . DIRECTORY_SEPARATOR . LOLIPOP_MIGRATOR_PLUGIN_NAME . DIRECTORY_SEPARATOR );

define( 'LOLIPOP_MIGRATOR_BACKUP_DIR', dirname( WP_CONTENT_DIR ) . DIRECTORY_SEPARATOR . 'wp-content-backup' . DIRECTORY_SEPARATOR );
define( 'LOLIPOP_MIGRATOR_BACKUP_TMP_DIR', LOLIPOP_MIGRATOR_BACKUP_DIR . LOLIPOP_MIGRATOR_PLUGIN_NAME . DIRECTORY_SEPARATOR . 'wp-content' . DIRECTORY_SEPARATOR );

define( 'LOLIPOP_MIGRATOR_COOKIE_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-cookie.txt' );
define( 'LOLIPOP_MIGRATOR_DUMP_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-dump.sql' );
define( 'LOLIPOP_MIGRATOR_LOG_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-migrate.log' );
define( 'LOLIPOP_MIGRATOR_ORIGIN_SERVER_LOG_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-migrate_origin_server.log' );
define( 'LOLIPOP_MIGRATOR_DUMP_ERROR_LOG_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-mysqldump-error.log' );
define( 'LOLIPOP_MIGRATOR_ARCHIVE_ERROR_LOG_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-archive-error.log' );
define( 'LOLIPOP_MIGRATOR_EXTRACT_ERROR_LOG_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-extract-error.log' );
define( 'LOLIPOP_MIGRATOR_RESTORE_ERROR_LOG_FILE', LOLIPOP_MIGRATOR_TMP_DIR . 'lolipop-migrator-restore-error.log' );

define( 'LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR', LOLIPOP_MIGRATOR_LIB_DIR . 'exception' . DIRECTORY_SEPARATOR );
define( 'LOLIPOP_MIGRATOR_BACKUP_LIB_DIR', LOLIPOP_MIGRATOR_LIB_DIR . 'backup' . DIRECTORY_SEPARATOR );
define( 'LOLIPOP_MIGRATOR_DUMPER_LIB_DIR', LOLIPOP_MIGRATOR_BACKUP_LIB_DIR . 'dumper' . DIRECTORY_SEPARATOR );
define( 'LOLIPOP_MIGRATOR_ARCHIVER_LIB_DIR', LOLIPOP_MIGRATOR_BACKUP_LIB_DIR . 'archiver' . DIRECTORY_SEPARATOR );
define( 'LOLIPOP_MIGRATOR_MIGRATE_LIB_DIR', LOLIPOP_MIGRATOR_LIB_DIR . 'migrate' . DIRECTORY_SEPARATOR );
define( 'LOLIPOP_MIGRATOR_EXTRACTOR_LIB_DIR', LOLIPOP_MIGRATOR_MIGRATE_LIB_DIR . 'extractor' . DIRECTORY_SEPARATOR );
define( 'LOLIPOP_MIGRATOR_RESTORER_LIB_DIR', LOLIPOP_MIGRATOR_MIGRATE_LIB_DIR . 'restorer' . DIRECTORY_SEPARATOR );

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR . 'class-lolipop-migrator-exception.php';
require_once LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR . 'class-lolipop-migrator-archive-exception.php';
require_once LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR . 'class-lolipop-migrator-dump-exception.php';
require_once LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR . 'class-lolipop-migrator-download-exception.php';
require_once LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR . 'class-lolipop-migrator-extract-exception.php';
require_once LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR . 'class-lolipop-migrator-restore-exception.php';
require_once LOLIPOP_MIGRATOR_EXCEPTION_LIB_DIR . 'class-lolipop-migrator-migrate-exception.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-log.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-file.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-response.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-server.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-remote-server.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-backup.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-migrate.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator-wordpress.php';
require_once LOLIPOP_MIGRATOR_LIB_DIR . 'class-lolipop-migrator.php';
require_once LOLIPOP_MIGRATOR_BACKUP_LIB_DIR . 'class-lolipop-migrator-dump.php';
require_once LOLIPOP_MIGRATOR_DUMPER_LIB_DIR . 'class-lolipop-migrator-base-dumper.php';
require_once LOLIPOP_MIGRATOR_DUMPER_LIB_DIR . 'class-lolipop-migrator-mysqldump-dumper.php';
require_once LOLIPOP_MIGRATOR_DUMPER_LIB_DIR . 'class-lolipop-migrator-wpdb-dumper.php';
require_once LOLIPOP_MIGRATOR_BACKUP_LIB_DIR . 'class-lolipop-migrator-archive.php';
require_once LOLIPOP_MIGRATOR_ARCHIVER_LIB_DIR . 'class-lolipop-migrator-base-archiver.php';
require_once LOLIPOP_MIGRATOR_ARCHIVER_LIB_DIR . 'class-lolipop-migrator-tar-command-archiver.php';
require_once LOLIPOP_MIGRATOR_ARCHIVER_LIB_DIR . 'class-lolipop-migrator-zip-command-archiver.php';
require_once LOLIPOP_MIGRATOR_ARCHIVER_LIB_DIR . 'class-lolipop-migrator-zip-extension-archiver.php';
require_once LOLIPOP_MIGRATOR_MIGRATE_LIB_DIR . 'class-lolipop-migrator-extract.php';
require_once LOLIPOP_MIGRATOR_EXTRACTOR_LIB_DIR . 'class-lolipop-migrator-base-extractor.php';
require_once LOLIPOP_MIGRATOR_EXTRACTOR_LIB_DIR . 'class-lolipop-migrator-tar-command-extractor.php';
require_once LOLIPOP_MIGRATOR_EXTRACTOR_LIB_DIR . 'class-lolipop-migrator-unzip-command-extractor.php';
require_once LOLIPOP_MIGRATOR_MIGRATE_LIB_DIR . 'class-lolipop-migrator-restore.php';
require_once LOLIPOP_MIGRATOR_RESTORER_LIB_DIR . 'class-lolipop-migrator-base-restorer.php';
require_once LOLIPOP_MIGRATOR_RESTORER_LIB_DIR . 'class-lolipop-migrator-mysql-command-restorer.php';

<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Mysql_Command_Restorer class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Database Restore By mysql command
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Mysql_Command_Restorer extends Lolipop_Migrator_Base_Restorer {
	/**
	 * データベースのリストアを行う
	 *
	 * @throws Lolipop_Migrator_Restore_Exception 処理に失敗した場合
	 */
	public function restore() {
		$class = get_class( $this );

		$mysql = Lolipop_Migrator_Server::which_mysql_command();

		$defaults_extra_file = Lolipop_Migrator_File::create_mysql_conf( LOLIPOP_MIGRATOR_TMP_DIR );
		if ( is_null( $defaults_extra_file ) ) {
			throw new Lolipop_Migrator_Restore_Exception( 'mysql で使用する設定ファイルの生成に失敗した' );
		}

		$command = $mysql . ' --defaults-extra-file=' . $defaults_extra_file . ' ' . DB_NAME
			. ' 2> ' . LOLIPOP_MIGRATOR_RESTORE_ERROR_LOG_FILE . ' < ' . LOLIPOP_MIGRATOR_DUMP_FILE;

		exec( $command, $output, $return_var );

		if ( ! Lolipop_Migrator_File::remove( $defaults_extra_file ) ) {
			Lolipop_Migrator_Log::error( 'mysql で使用する設定ファイルの削除に失敗した' );
		}

		$content = Lolipop_Migrator_File::get_contents( LOLIPOP_MIGRATOR_RESTORE_ERROR_LOG_FILE );
		Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_RESTORE_ERROR_LOG_FILE );

		if ( 0 !== $return_var ) {
			$message = sprintf( '[error] restorer:%s - データベースのリストアに失敗した - %s', $class, $content );
			throw new Lolipop_Migrator_Restore_Exception( $message );
		}
	}
}

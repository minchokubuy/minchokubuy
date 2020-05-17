<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Mysqldump_Dumper class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Database Dump By Mysqldump
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Mysqldump_Dumper extends Lolipop_Migrator_Base_Dumper {
	/**
	 * データベースのダンプを行う
	 *
	 * @throws Lolipop_Migrator_Dump_Exception 処理に失敗した場合
	 */
	public function dump() {
		$class = get_class( $this );

		$mysqldump    = Lolipop_Migrator_Server::which_mysqldump_command();
		$event_option = $this->has_event_privilege( $this->get_privileges() ) ? '-E' : '';

		$defaults_extra_file = Lolipop_Migrator_File::create_mysql_conf( LOLIPOP_MIGRATOR_TMP_DIR );
		if ( is_null( $defaults_extra_file ) ) {
			throw new Lolipop_Migrator_Dump_Exception( 'mysqldump で使用する設定ファイルの生成に失敗した' );
		}

		$command = $mysqldump . ' --defaults-extra-file=' . $defaults_extra_file
			. ' -R ' . $event_option . ' ' . DB_NAME
			. ' 2> ' . LOLIPOP_MIGRATOR_DUMP_ERROR_LOG_FILE . ' > ' . LOLIPOP_MIGRATOR_DUMP_FILE;

		exec( $command, $output, $return_var );

		if ( ! Lolipop_Migrator_File::remove( $defaults_extra_file ) ) {
			Lolipop_Migrator_Log::error( 'mysqldump で使用する設定ファイルの削除に失敗した' );
		}

		$content = Lolipop_Migrator_File::get_contents( LOLIPOP_MIGRATOR_DUMP_ERROR_LOG_FILE );
		Lolipop_Migrator_File::remove( LOLIPOP_MIGRATOR_DUMP_ERROR_LOG_FILE );

		if ( 0 !== $return_var ) {
			$message = sprintf( '[error] dumper:%s - ダンプファイルの出力に失敗した - %s', $class, $content );
			throw new Lolipop_Migrator_Dump_Exception( $message );
		}

		$this->replace_definer();
	}

	/**
	 * ダンプファイルから DEFINER 関連の記述を削除する
	 */
	protected function replace_definer() {
		$is_bsd = strpos( Lolipop_Migrator_Server::os(), 'BSD' ) > 0;

		if ( $is_bsd ) {
			$cmd = sprintf( "sed -i '' -e '/^\/\*!50013 DEFINER=/d' %s", LOLIPOP_MIGRATOR_DUMP_FILE );
		} else {
			$cmd = sprintf( "sed -i -e '/^\/\*!50013 DEFINER=/d' %s", LOLIPOP_MIGRATOR_DUMP_FILE );
		}

		exec( $cmd, $output, $return_var );
		if ( 0 !== $return_var ) {
			$message = sprintf( 'DEFINER コメントの削除に失敗した[file: %s]', LOLIPOP_MIGRATOR_DUMP_FILE );
			Lolipop_Migrator_Log::error( $message );
		}

		if ( $is_bsd ) {
			$cmd = sprintf( "sed -i '' -r 's/CREATE DEFINER=.+ (FUNCTION|PROCEDURE)/CREATE \\1/g' %s", LOLIPOP_MIGRATOR_DUMP_FILE );
		} else {
			$cmd = sprintf( "sed -i -E 's/CREATE DEFINER=.+\s(FUNCTION|PROCEDURE)/CREATE \\1/g' %s", LOLIPOP_MIGRATOR_DUMP_FILE );
		}

		exec( $cmd, $output, $return_var );
		if ( 0 !== $return_var ) {
			$message = sprintf( 'CREATE DEFINER の削除に失敗した[file: %s]', LOLIPOP_MIGRATOR_DUMP_FILE );
			Lolipop_Migrator_Log::error( $message );
		}

		if ( $is_bsd ) {
			$cmd = sprintf( "sed -i '' -r 's/\/\*\!50020 DEFINER=`.*`@`localhost`\*\/ //g' %s", LOLIPOP_MIGRATOR_DUMP_FILE );
		} else {
			$cmd = sprintf( "sed -i -E 's/\/\*\!50020 DEFINER=`.*`@`localhost`\*\/ //g' %s", LOLIPOP_MIGRATOR_DUMP_FILE );
		}

		exec( $cmd, $output, $return_var );
		if ( 0 !== $return_var ) {
			$message = sprintf( 'DEFINER localhost コメントの削除に失敗した[file: %s]', LOLIPOP_MIGRATOR_DUMP_FILE );
			Lolipop_Migrator_Log::error( $message );
		}
	}
}

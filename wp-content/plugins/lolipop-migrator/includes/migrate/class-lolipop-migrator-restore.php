<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Restore class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Restore Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Restore {
	/**
	 * データベースのダンプをリストアする
	 *
	 * @throws Lolipop_Migrator_Restore_Exception 処理に失敗した場合
	 */
	public function restore() {
		$restorer = null;

		if ( false !== Lolipop_Migrator_Server::which_mysql_command() ) {
			$restorer = new Lolipop_Migrator_Mysql_Command_Restorer();
		}

		if ( is_null( $restorer ) ) {
			$message = '適切なリストアクラスが選択できなかった';
			throw new Lolipop_Migrator_Restore_Exception( $message );
		}

		$class = get_class( $restorer );
		Lolipop_Migrator_Log::info( sprintf( '[start] restorer:%s - データベースのリストアを開始', $class ) );

		$restorer->restore();

		Lolipop_Migrator_Log::info( sprintf( '[end] restorer:%s - データベースのリストアを終了', $class ) );
	}
}

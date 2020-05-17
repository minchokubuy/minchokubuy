<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Dump class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Database Dump Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Dump {
	/**
	 * 処理を行うインスタンス
	 *
	 * @var Lolipop_Migrator_Base_Dumper
	 */
	protected $dumper;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		if ( Lolipop_Migrator_Server::is_disable( 'exec' ) ) {
			$this->dumper = new Lolipop_Migrator_Wpdb_Dumper();
		} elseif ( false !== Lolipop_Migrator_Server::which_mysqldump_command() ) {
			$this->dumper = new Lolipop_Migrator_Mysqldump_Dumper();
		} else {
			$this->dumper = new Lolipop_Migrator_Wpdb_Dumper();
		}
	}

	/**
	 * データベースのダンプを行う
	 *
	 * @throws Lolipop_Migrator_Dump_Exception 処理に失敗した場合
	 */
	public function dump() {
		$class = get_class( $this->dumper );

		if ( Lolipop_Migrator_Server::is_disable( 'exec' ) ) {
			Lolipop_Migrator_Log::info( sprintf( '[start] dumper:%s - データベースのダンプを開始', $class ) );
			$this->dumper->dump();
			Lolipop_Migrator_Log::info( sprintf( '[end] dumper:%s - データベースのダンプを終了', $class ) );
			return;
		}

		try {
			Lolipop_Migrator_Log::info( sprintf( '[start] dumper:%s - データベースのダンプを開始', $class ) );
			$this->dumper->dump();
			Lolipop_Migrator_Log::info( sprintf( '[end] dumper:%s - データベースのダンプを終了', $class ) );
			return;
		} catch ( Lolipop_Migrator_Dump_Exception $e ) {
			if ( 'Lolipop_Migrator_Wpdb_Dumper' === get_class( $this->dumper ) ) {
				throw $e;
			}

			Lolipop_Migrator_Log::error( $e->getMessage() );
		}

		$this->dumper = new Lolipop_Migrator_Wpdb_Dumper();
		$class        = get_class( $this->dumper );
		Lolipop_Migrator_Log::info( sprintf( '[retry start] dumper:%s - データベースのダンプを開始', $class ) );
		$this->dumper->dump();
		Lolipop_Migrator_Log::info( sprintf( '[retry end] dumper:%s - データベースのダンプを終了', $class ) );
	}
}

<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Base_Restorer class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Database Restore Base Class
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Base_Restorer {
	/**
	 * wpdb のインスタンス
	 *
	 * @var wpdb
	 */
	protected $wpdb;

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}
}

<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Base_Dumper class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Database Dump Base Class
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Base_Dumper {
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

	/**
	 * 権限情報を返却する
	 *
	 * @return array
	 */
	public function get_privileges() {
		$result = array();

		$grants = $this->wpdb->get_results( 'SHOW GRANTS FOR CURRENT_USER', ARRAY_N );

		foreach ( $grants as $grant ) {
			preg_match( '/^GRANT (?<privileges>.*) ON/', $grant[0], $matches );
			$result[] = $matches['privileges'];
		}

		return $result;
	}

	/**
	 * EVENT の参照権限があるかどうかを返却
	 *
	 * @param array $privileges 権限の配列
	 * @return bool
	 */
	public function has_event_privilege( $privileges ) {
		$result = false;

		foreach ( $privileges as $privilege ) {
			if ( ( strpos( $privilege, 'ALL PRIVILEGES' ) !== false ) || ( strpos( $privilege, 'EVENT' ) !== false ) ) {
				$result = true;
				break;
			}
		}

		return $result;
	}

	/**
	 * TRIGGER の参照権限があるかどうかを返却
	 *
	 * @param array $privileges 権限の配列
	 * @return bool
	 */
	public function has_trigger_privilege( $privileges ) {
		$result = false;

		foreach ( $privileges as $privilege ) {
			if ( ( strpos( $privilege, 'ALL PRIVILEGES' ) !== false ) || ( strpos( $privilege, 'TRIGGER' ) !== false ) ) {
				$result = true;
				break;
			}
		}

		return $result;
	}
}

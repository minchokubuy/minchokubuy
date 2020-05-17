<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Log class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Log Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Log {
	/**
	 * このクラスの唯一のインスタンス
	 *
	 * @var static
	 */
	protected static $instance;

	/**
	 * オリジンサーバーモードかどうか
	 *
	 * @var bool
	 */
	protected $is_origin_server;

	/**
	 * コンストラクタ
	 */
	protected function __construct() {
	}

	/**
	 * インスタンスを返却
	 *
	 * @return static
	 */
	public static function get_instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance                   = new static();
			static::$instance->is_origin_server = false;
		}

		return static::$instance;
	}

	/**
	 * モードを設定する
	 *
	 * @param bool $is_origin_server オリジンサーバーモードかどうか
	 */
	public static function set_mode( $is_origin_server ) {
		$instance                   = static::get_instance();
		$instance->is_origin_server = $is_origin_server;
	}

	/**
	 * ログ出力
	 *
	 * @param mixed  $message 出力するメッセージ
	 * @param string $level   ログレベル
	 */
	protected function output( $message, $level = 'FATAL' ) {
		if ( is_array( $message ) ) {
			if ( version_compare( Lolipop_Migrator_Server::wordpress_version(), '4.1', '<' ) ) {
				$message = json_encode( $message );
			} else {
				$message = wp_json_encode( $message );
			}
		}

		if ( $this->is_origin_server ) {
			$log_file = LOLIPOP_MIGRATOR_ORIGIN_SERVER_LOG_FILE;
		} else {
			$log_file = LOLIPOP_MIGRATOR_LOG_FILE;
		}

		$message = sprintf( "%s - %s - %s\n", date( 'Y-m-d H:i:s' ), $level, $message );
		error_log( $message, 3, $log_file );
	}

	/**
	 * ログ出力(致命的なエラー)
	 *
	 * @param mixed $message 出力するメッセージ
	 */
	public static function fatal( $message ) {
		$instance = static::get_instance();
		$instance->output( $message );
	}

	/**
	 * ログ出力(エラー)
	 *
	 * @param mixed $message 出力するメッセージ
	 */
	public static function error( $message ) {
		$instance = static::get_instance();
		$instance->output( $message, 'ERROR' );
	}

	/**
	 * ログ出力(警告)
	 *
	 * @param mixed $message 出力するメッセージ
	 */
	public static function warn( $message ) {
		$instance = static::get_instance();
		$instance->output( $message, 'WARN' );
	}

	/**
	 * ログ出力(情報)
	 *
	 * @param mixed $message 出力するメッセージ
	 */
	public static function info( $message ) {
		$instance = static::get_instance();
		$instance->output( $message, 'INFO' );
	}

	/**
	 * ログ出力(デバッグ用の情報)
	 *
	 * @param mixed $message 出力するメッセージ
	 */
	public static function debug( $message ) {
		$instance = static::get_instance();
		$instance->output( $message, 'DEBUG' );
	}

	/**
	 * ログ出力(トレース情報)
	 *
	 * @param mixed $message 出力するメッセージ
	 */
	public static function trace( $message ) {
		$instance = static::get_instance();
		$instance->output( $message, 'TRACE' );
	}
}

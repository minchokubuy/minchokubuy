<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_Response class
 *
 * @package Lolipop_Migrator
 * @since 0.1.0
 */

/**
 * Lolipop Migrator Response
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_Response {
	/**
	 * 成功時のレスポンスを出力
	 *
	 * @param mixed $response 出力データ
	 * @param int   $status_code ステータスコード
	 */
	public static function success( $response, $status_code = 200 ) {
		wp_send_json_success( $response, $status_code );
	}

	/**
	 * エラー時のレスポンスを出力
	 *
	 * @param string $category エラーのカテゴリ
	 * @param string $message エラーメッセージ
	 * @param int    $status_code ステータスコード
	 * @param bool   $is_output_log ログ出力するかどうか
	 */
	public static function error( $category, $message, $status_code = 500, $is_output_log = true ) {
		if ( true === $is_output_log ) {
			Lolipop_Migrator_Log::error( $message );
		}

		$response = array(
			'category' => $category,
			'message'  => $message,
		);
		wp_send_json_error( $response, $status_code );
	}
}

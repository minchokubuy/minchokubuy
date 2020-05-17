<?php
/**
 * Lolipop Migrator: Lolipop_Migrator_WordPress class
 *
 * @package Lolipop_Migrator
 * @since 0.9.0
 */

/**
 * Lolipop Migrator WordPress Utility
 *
 * @since 0.1.0
 */
class Lolipop_Migrator_WordPress {
	/**
	 * サイトURLを変更する
	 *
	 * @param  string $site_url 書き換え後のURL
	 * @return array
	 */
	public function change_site_url( $site_url ) {
		$result = false;

		if ( ! empty( $site_url ) ) {
			$result = update_option( 'siteurl', $site_url );
			if ( $result ) {
				$result = update_option( 'home', $site_url );
			}
		}

		if ( ! $result ) {
			return array(
				'error'   => 'wordpress',
				'message' => 'サイトURLの変更に失敗した',
			);
		}

		return array(
			'error'    => null,
			'response' => array(
				'message' => 'サイトURLの変更に成功した',
			),
		);
	}
}

<?php
/**
 * Lolipop Migrator
 *
 * @package Lolipop_Migrator
 *
 * Plugin Name: Lolipop Migrator
 * Plugin URI:  https://lolipop.jp/
 * Description: ロリポップ！レンタルサーバーの「WordPress簡単引っ越し」機能が利用するプラグインです。
 * Version:     0.9.6
 * Author:      GMO Pepabo, Inc.
 * Author URI:  https://pepabo.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: lolipop-migrator
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'LOLIPOP_MIGRATOR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOLIPOP_MIGRATOR_LIB_DIR', LOLIPOP_MIGRATOR_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR );

require_once LOLIPOP_MIGRATOR_LIB_DIR . 'bootstrap.php';

register_activation_hook( __FILE__, array( 'Lolipop_Migrator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Lolipop_Migrator', 'deactivate' ) );

add_action( 'plugins_loaded', 'load_lolipop_migrator' );

/**
 * プラグインのロード処理
 */
function load_lolipop_migrator() {
	Lolipop_Migrator::load();
}

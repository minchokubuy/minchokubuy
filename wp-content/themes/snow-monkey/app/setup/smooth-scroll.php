<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 10.4.7
 */

use Framework\Helper;

/**
 * Enqueue smooth scroll script
 *
 * @return void
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_script(
			Helper::get_main_script_handle() . '-smooth-scroll',
			get_theme_file_uri( '/assets/js/smooth-scroll.min.js' ),
			[ 'jquery' ],
			filemtime( get_theme_file_path( '/assets/js/smooth-scroll.min.js' ) ),
			true
		);
	}
);
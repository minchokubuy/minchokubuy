<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 10.2.0
 */

use Framework\Helper;
use Inc2734\WP_Customizer_Framework\Framework;

Framework::control(
	'select',
	'archive-eyecatch',
	[
		'label'       => __( 'Eyecatch image', 'snow-monkey' ),
		'description' => __( 'Select how to display the eyecatch image in archive page.', 'snow-monkey' ),
		'priority'    => 110,
		'default'     => 'page-header',
		'choices'     => Helper::eyecatch_position_choices(),
	]
);

if ( ! is_customize_preview() ) {
	return;
}

$panel   = Framework::get_panel( 'design' );
$section = Framework::get_section( 'design-archive' );
$control = Framework::get_control( 'archive-eyecatch' );
$control->join( $section )->join( $panel );

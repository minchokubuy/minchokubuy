<?php
/**
 * @package snow-monkey
 * @author inc2734
 * @license GPL-2.0+
 * @version 7.7.0
 */

use Inc2734\WP_Customizer_Framework\Framework;
use Framework\Helper;

Framework::control(
	'text',
	'infobar-content',
	[
		'label'     => __( 'Infobar content', 'snow-monkey' ),
		'transport' => 'postMessage',
		'priority'  => 100,
	]
);

if ( ! is_customize_preview() ) {
	return;
}

$section = Framework::get_section( 'infobar' );
$control = Framework::get_control( 'infobar-content' );
$control->join( $section );
$control->partial(
	[
		'selector'            => '.p-infobar',
		'container_inclusive' => true,
		'render_callback'     => function() {
			if ( get_theme_mod( 'infobar-content' ) ) {
				Helper::get_template_part( 'template-parts/common/infobar' );
			}
		},
	]
);

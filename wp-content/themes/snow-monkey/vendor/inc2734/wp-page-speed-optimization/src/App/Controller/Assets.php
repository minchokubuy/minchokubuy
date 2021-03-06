<?php
/**
 * @package inc2734/wp-page-speed-optimization
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Inc2734\WP_Page_Speed_Optimization\App\Controller;

class Assets {

	public function __construct() {
		if ( is_admin() ) {
			return;
		}

		add_action( 'wp_head', [ $this, '_optimize_jquery_loading' ], 2 );
		add_action( 'wp_head', [ $this, '_optimize_snow_monkey_scripts' ], 2 );
		add_filter( 'script_loader_tag', [ $this, '_set_defer' ], 10, 3 );
		add_filter( 'script_loader_tag', [ $this, '_set_async' ], 10, 3 );

		add_filter( 'script_loader_tag', [ $this, '_builded' ], 10, 3 );
		add_filter( 'style_loader_tag', [ $this, '_set_preload_stylesheet' ], 10, 3 );
		add_action( 'wp_footer', [ $this, '_build_stylesheet_link' ], 99999 );
	}

	/**
	 * Optimize jQuery loading
	 *
	 * jQuery loads in footer and Invalidate jquery-migrate
	 *
	 * @return void
	 */
	public function _optimize_jquery_loading() {
		if ( ! $this->_is_optimize_jquery_loading() ) {
			return;
		}

		if ( in_array( $GLOBALS['pagenow'], [ 'wp-login.php', 'wp-register.php' ] ) ) {
			return;
		}

		$jquery = wp_scripts()->query( 'jquery-core', 'registered' );
		$jquery_ver = $jquery->ver;
		$jquery_src = $jquery->src;

		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-core' );
		wp_register_script( 'jquery', false, [ 'jquery-core' ], $jquery_ver );
		wp_register_script( 'jquery-core', $jquery_src, [], $jquery_ver );
		wp_scripts()->add_data( 'jquery', 'defer', true );
		wp_scripts()->add_data( 'jquery-core', 'defer', true );

		// $handle is included in $this->maybe_add_defer_handles
		// One of the $handle deps is included in $this->maybe_add_defer_handles
		foreach ( wp_scripts()->queue as $handle ) {
			$dependency = wp_scripts()->query( $handle, 'registered' );
			if ( in_array( 'jquery', $dependency->deps ) || in_array( 'jquery-core', $dependency->deps ) ) {
				if ( wp_scripts()->get_data( $handle, 'after' ) ) {
					wp_scripts()->add_data( 'jquery', 'defer', false );
					wp_scripts()->add_data( 'jquery-core', 'defer', false );
					continue;
				}

				wp_scripts()->add_data( $handle, 'defer', true );

				// Remove in_footer
				$dependency->args = null;
				wp_scripts()->add_data( $handle, 'group', null );
			}
		}

		add_filter(
			'script_loader_tag',
			function( $tag, $handle ) {
				$dependency = wp_scripts()->query( $handle, 'registered' );

				foreach ( $dependency->deps as $deps_handle ) {
					if ( wp_scripts()->get_data( $handle, 'after' ) ) {
						wp_scripts()->add_data( 'jquery', 'defer', false );
						wp_scripts()->add_data( 'jquery-core', 'defer', false );
						continue;
					}

					$deps_dependency = wp_scripts()->query( $deps_handle, 'registered' );
					if ( in_array( 'jquery', $deps_dependency->deps )
						|| in_array( 'jquery-core', $deps_dependency->deps )
						|| wp_scripts()->get_data( $deps_handle, 'defer' )
						|| wp_scripts()->get_data( $deps_handle, 'async' )
					) {
						wp_scripts()->add_data( $handle, 'defer', true );
					}
				}

				return $tag;
			},
			9,
			2
		);
	}

	/**
	 * defer/async script move to head
	 *
	 * @return void
	 */
	public function _optimize_snow_monkey_scripts() {
		$handles = array_merge(
			$this->_get_defer_handles(),
			$this->_get_async_handles()
		);

		if ( ! $handles ) {
			return;
		}

		foreach ( $handles as $handle ) {
			$dependency = wp_scripts()->query( $handle, 'registered' );
			if ( $dependency ) {
				// Remove in_footer
				$dependency->args = null;
				wp_scripts()->add_data( $handle, 'group', null );
			}
		}

		foreach ( $this->_get_defer_handles() as $handle ) {
			wp_scripts()->add_data( $handle, 'defer', true );
		}

		foreach ( $this->_get_async_handles() as $handle ) {
			wp_scripts()->add_data( $handle, 'async', true );
		}
	}

	/**
	 * Set defer
	 *
	 * @param string $tag
	 * @param string handle
	 * @param string src
	 * @return string
	 */
	public function _set_defer( $tag, $handle, $src ) {
		if ( false !== strpos( $tag, ' defer' ) || false !== strpos( $tag, ' async' ) ) {
			return $tag;
		}

		if ( ! wp_scripts()->get_data( $handle, 'defer' ) ) {
			return $tag;
		}

		return str_replace( ' src', ' defer src', $tag );
	}

	/**
	 * Set async
	 *
	 * @param string $tag
	 * @param string handle
	 * @param string src
	 * @return string
	 */
	public function _set_async( $tag, $handle, $src ) {
		if ( false !== strpos( $tag, ' defer' ) || false !== strpos( $tag, ' async' ) ) {
			return $tag;
		}

		if ( ! wp_scripts()->get_data( $handle, 'async' ) ) {
			return $tag;
		}

		return str_replace( ' src', ' async src', $tag );
	}

	/**
	 * Re-build script tag
	 * only in_footer param is true
	 *
	 * @param string $tag
	 * @param string handle
	 * @param string src
	 * @return string
	 */
	public function _builded( $tag, $handle, $src ) {
		$handles = apply_filters( 'inc2734_wp_page_speed_optimization_builded_scripts', [] );
		if ( ! $handles ) {
			return $tag;
		}

		if ( ! in_array( $handle, $handles ) ) {
			return $tag;
		}

		return sprintf(
			'<script>
			document.addEventListener("DOMContentLoaded", function(event) {
				var s=document.createElement("script");
				s.src="%s";
				s.async=true;document.body.appendChild(s);
			});
			</script>',
			$src
		);
	}

	/**
	 * Set rel="preload" for stylesheet
	 *
	 * @param string $tag
	 * @param string handle
	 * @param string src
	 * @return string
	 */
	public function _set_preload_stylesheet( $tag, $handle, $src ) {
		$handles = apply_filters( 'inc2734_wp_page_speed_optimization_output_head_styles', [] );
		if ( in_array( $handle, $handles ) && 0 === strpos( $src, site_url() ) ) {
			$sitepath = site_url( '', 'relative' );
			$abspath  = untrailingslashit( ABSPATH );

			if ( $sitepath ) {
				$abspath = preg_replace( '|(.*?)' . preg_quote( $sitepath ) . '$|', '$1', $abspath );
			}

			$parse  = parse_url( $src );
			$buffer = \file_get_contents( $abspath . $parse['path'] );
			$buffer = preg_replace( '|(url\(\s*?[\'"]?)./|', '$1' . dirname( $parse['path'] ) . '/', $buffer );
			$buffer = preg_replace( '|(url\(\s*?[\'"]?)../|', '$1' . dirname( $parse['path'] ) . '/../', $buffer );
			$buffer = preg_replace( '|(url\(\s*?[\'"]?)//|', '$1/', $buffer );
			$buffer = str_replace( [ "\n\r", "\n", "\r", "\t" ], '', $buffer );
			$buffer = preg_replace( '|{\s*|', '{', $buffer );
			$buffer = preg_replace( '|}\s*|', '}', $buffer );
			$buffer = preg_replace( '|;\s*|', ';', $buffer );
			$buffer = preg_replace( '|@charset .+?;|', '', $buffer );
			$buffer = preg_replace( '|/\*.*?\*/|', '', $buffer );

			$media = preg_match( '|media=\'([^\']*?)\'|', $tag, $match )
				? $match[1]
				: 'all';
			?>
			<!-- <?php echo $tag; // xss ok. ?> -->
			<style media="<?php echo esc_attr( $media ); ?>"><?php echo $buffer; // xss ok. ?></style>
			<?php
			return;
		}

		$handles = apply_filters( 'inc2734_wp_page_speed_optimization_preload_stylesheets', [] );
		if ( in_array( $handle, $handles ) ) {
			?>
			<!-- <?php echo $tag; // xss ok. ?> -->
			<?php
			$preload_for_legacy = str_replace( 'media=\'all\'', 'media="print"', $tag );
			$preload_for_legacy = str_replace( '/>', 'onload="this.media=\'all\'" />', $preload_for_legacy );
			$preload_for_modern = str_replace( 'rel=\'stylesheet\'', 'rel="preload"', $tag );
			$preload_for_modern = str_replace( '/>', 'as="style" />', $preload_for_modern );
			return $preload_for_legacy . $preload_for_modern;
		}

		return $tag;
	}

	/**
	 * Builed stylesheet link tag
	 *
	 * @return void
	 */
	public function _build_stylesheet_link() {
		if ( ! apply_filters( 'inc2734_wp_page_speed_optimization_preload_stylesheets', [] ) ) {
			return;
		}

		// @codingStandardsIgnoreStart
		?>
<script>
var l = document.getElementsByTagName('link');
for (var i = l.length - 1; i > 0; i--) {
if ('style' === l[i].getAttribute('as') && 'preload' === l[i].getAttribute('rel')) {
var s = document.createElement('link');
s.setAttribute('rel', 'stylesheet');
s.setAttribute('href', l[i].getAttribute('href'));
s.setAttribute('media', l[i].getAttribute('media'));
document.head.appendChild(s);
}
}
</script>
		<?php
		// @codingStandardsIgnoreEnd
	}

	/**
	 * Return defer script handles
	 *
	 * @return array
	 */
	protected function _is_optimize_jquery_loading() {
		return apply_filters( 'inc2734_wp_page_speed_optimization_optimize_jquery_loading', false );
	}

	/**
	 * Return defer script handles
	 *
	 * @return array
	 */
	protected function _get_defer_handles() {
		return apply_filters( 'inc2734_wp_page_speed_optimization_defer_scripts', [] );
	}

	/**
	 * Return async script handles
	 *
	 * @return array
	 */
	protected function _get_async_handles() {
		return apply_filters( 'inc2734_wp_page_speed_optimization_async_scripts', [] );
	}
}

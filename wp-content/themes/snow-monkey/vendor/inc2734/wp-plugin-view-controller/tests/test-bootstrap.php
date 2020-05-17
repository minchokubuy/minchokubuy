<?php
class BootstrapTest extends WP_UnitTestCase {

	public function setup() {
		parent::setup();

		$this->prefix     = 'inc2734_wp_plugin_view_controller_';
		$this->controller = new \Inc2734\WP_Plugin_View_Controller\Bootstrap(
			[
				'prefix' => $this->prefix,
				'path'   => untrailingslashit( __DIR__ ) . '/templates',
			]
		);
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function view_args() {
		add_filter(
			$this->prefix . 'view_args',
			function( $args ) {
				$args['slug'] = 'slug2';
				$args['name'] = 'name2';
				$args['vars'] = [ 'key2' => 'value2' ];
				return $args;
			}
		);

		add_action(
			$this->prefix . 'view_pre_render',
			function( $args ) {
				$this->assertEquals( 'slug2', $args['slug'] );
				$this->assertEquals( 'name2', $args['name'] );
				$this->assertEquals( [ 'key2' => 'value2' ], $args['vars'] );
			}
		);

		$this->controller->render( 'slug', 'name', [ 'key' => 'value' ] );
	}

	/**
	 * @test
	 */
	public function define_html() {
		add_action(
			$this->prefix . 'view_slug',
			function( $name, $vars ) {
				?>
				slug2
				<?php
			},
			10,
			2
		);

		ob_start();
		$this->controller->render( 'slug', 'name', [ 'key' => 'value' ] );
		$this->assertEquals( 'slug2', trim( ob_get_clean() ) );
	}

	/**
	 * @test
	 */
	public function define_html_with_name() {
		add_action(
			$this->prefix . 'view_slug-name',
			function( $vars ) {
				?>
				slug2-name2
				<?php
			}
		);

		ob_start();
		$this->controller->render( 'slug', 'name', [ 'key' => 'value' ] );
		$this->assertEquals( 'slug2-name2', trim( ob_get_clean() ) );
	}

	/**
	 * @test
	 */
	public function define_html_prioritize_name() {
		add_action(
			$this->prefix . 'view_slug-name',
			function( $vars ) {
				?>
				slug2-name2
				<?php
			}
		);

		add_action(
			$this->prefix . 'view_slug',
			function( $name, $vars ) {
				?>
				slug2
				<?php
			},
			10,
			2
		);

		ob_start();
		$this->controller->render( 'slug', 'name', [ 'key' => 'value' ] );
		$this->assertEquals( 'slug2-name2', trim( ob_get_clean() ) );
	}

	/**
	 * @test
	 */
	public function view_render() {
		add_filter(
			$this->prefix . 'view_render',
			function( $html, $slug, $name, $vars ) {
				return 'slug2';
			},
			10,
			4
		);

		ob_start();
		$this->controller->render( 'slug', 'name', [ 'key' => 'value' ] );
		$this->assertEquals( 'slug2', trim( ob_get_clean() ) );
	}

	/**
	 * @test
	 */
	public function view_render_with_define_html() {
		add_action(
			$this->prefix . 'view_slug-name',
			function( $vars ) {
				?>
				slug2-name2
				<?php
			}
		);

		add_filter(
			$this->prefix . 'view_render',
			function( $html, $slug, $name, $vars ) {
				return str_replace( 'slug2', 'slug3', $html );
			},
			10,
			4
		);

		ob_start();
		$this->controller->render( 'slug', 'name', [ 'key' => 'value' ] );
		$this->assertEquals( 'slug3-name2', trim( ob_get_clean() ) );
	}
}

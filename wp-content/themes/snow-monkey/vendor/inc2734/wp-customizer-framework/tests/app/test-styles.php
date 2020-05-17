<?php
use Inc2734\WP_Customizer_Framework\Customizer_Framework;
use Inc2734\WP_Customizer_Framework\Style;

class Inc2734_WP_Customizer_Framework_Styles_Test extends WP_UnitTestCase {

	public function setup() {
		parent::setup();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @test
	 * @runInSeparateProcess
	 */
	public function register() {
		$cfs = Customizer_Framework::styles();
		$cfs->register(
			[
				'body',
			],
			[
				'font-size' => '16px',
				'color' => '#000',
			],
			'@media (min-width: 1024px)'
		);

		$this->assertEquals(
			[
				[
					'selectors' => [
						'body',
					],
					'properties' => [
						'font-size' => '16px',
						'color'     => '#000',
					],
					'media_query' => '@media (min-width: 1024px)',
				],
			],
			Style::get_registerd_styles()
		);
	}

	/**
	 * @test
	 */
	public function light() {
		$cfs = Customizer_Framework::styles();
		$this->assertEquals( '#7ed5d7', $cfs->light( '#38b3b7' ) );
		$this->assertEquals( '#ffffff', $cfs->light( '#ffffff' ) );
	}

	/**
	 * @test
	 */
	public function lighter() {
		$cfs = Customizer_Framework::styles();
		$this->assertEquals( '#b2e6e8', $cfs->lighter( '#38b3b7' ) );
	}

	/**
	 * @test
	 */
	public function lightest() {
		$cfs = Customizer_Framework::styles();
		$this->assertEquals( '#c0eaec', $cfs->lightest( '#38b3b7' ) );
	}

	/**
	 * @test
	 */
	public function dark() {
		$cfs = Customizer_Framework::styles();
		$this->assertEquals( '#206769', $cfs->dark( '#38b3b7' ) );
		$this->assertEquals( '#000000', $cfs->dark( '#000000' ) );
	}

	/**
	 * @test
	 */
	public function darker() {
		$cfs = Customizer_Framework::styles();
		$this->assertEquals( '#103334', $cfs->darker( '#38b3b7' ) );
	}

	/**
	 * @test
	 */
	public function darkest() {
		$cfs = Customizer_Framework::styles();
		$this->assertEquals( '#0c2627', $cfs->darkest( '#38b3b7' ) );
	}
}

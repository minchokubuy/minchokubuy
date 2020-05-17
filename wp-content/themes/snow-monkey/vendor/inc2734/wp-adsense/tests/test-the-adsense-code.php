<?php
use Inc2734\WP_Adsense\Helper;

class The_Adsense_Code_Test extends WP_UnitTestCase {

	public function setup() {
		parent::setup();
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function big_banner() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="horizontal"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'big-banner' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function large_mobile() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="horizontal"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'large-mobile' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function large_sky_scraper() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="vertical"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'large-sky-scraper' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function rectangle_big() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="rectangle"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'rectangle-big' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function rectangle() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="rectangle"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'rectangle' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function rectangle_big_2() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="rectangle"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'rectangle-big-2' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function rectangle_2() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="rectangle"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'rectangle-2' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function link() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="link"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code(), 'link' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function nosize() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code() );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function has_script() {
		$expected = '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="auto"></ins><script>(adsbygoogle = window.adsbygoogle || []).push({});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_code() . '<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function autoads() {
		$expected = '<script>(adsbygoogle = window.adsbygoogle || []).push({google_ad_client: "ca-pub-1980725206584595", enable_page_level_ads: true});</script>';

		ob_start();
		Helper::the_adsense_code( $this->_autoads_code() );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	/**
	 * @test
	 */
	public function autoads_has_script() {
		$expected = '<script>(adsbygoogle = window.adsbygoogle || []).push({google_ad_client: "ca-pub-1980725206584595", enable_page_level_ads: true});</script>';

		ob_start();
		Helper::the_adsense_code( '<script>' . $this->_autoads_code() . '</script>' );
		$this->assertEquals( $expected, ob_get_clean() );
	}

	protected function _code() {
		return '<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-xxxxx" data-ad-slot="xxxxx" data-ad-format="auto"></ins>';
	}

	protected function _autoads_code() {
		return '(adsbygoogle = window.adsbygoogle || []).push({google_ad_client: "ca-pub-1980725206584595", enable_page_level_ads: true});';
	}
}

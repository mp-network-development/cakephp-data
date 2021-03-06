<?php

namespace Data\Test\TestCase\View\Helper;

use Cake\View\View;
use Data\View\Helper\DataHelper;
use Tools\TestSuite\TestCase;

class DataHelperTest extends TestCase {

	/**
	 * @var \Data\View\Helper\DataHelper
	 */
	public $DataHelper;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->DataHelper = new DataHelper(new View(null));
	}

	/**
	 * @return void
	 */
	public function testGetCountryIconPaths() {
		$result = $this->DataHelper->getCountryIconPaths();
		$this->assertNotEmpty($result);
	}

	/**
	 * @return void
	 */
	public function testCountryIcon() {
		$result = $this->DataHelper->countryIcon('de');
		$expected = '<img src="/data/img/country_flags/de.gif" alt="de" title="DE"/>';
		$this->assertSame($expected, $result);
	}

	/**
	 * @return void
	 */
	public function testLanguageFlag() {
		$result = $this->DataHelper->languageFlag('de');
		$expected = '<img src="/language_flags/de.gif" alt="de" title="DE"/>';
		$this->assertSame($expected, $result);
	}

}

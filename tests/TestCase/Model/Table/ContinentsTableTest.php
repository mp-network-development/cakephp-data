<?php

namespace Data\Test\TestCase\Model\Table;

use Data\Model\Continent;
use Tools\TestSuite\TestCase;

class ContinentsTableTest extends TestCase {

	public $fixtures = array(
		'plugin.data.continents'
	);

	public $Continent;

	public function setUp() {
		parent::setUp();

		$this->Continent = TableRegistry::get('Data.Continent');
	}

	public function testObject() {
		$this->assertTrue(is_object($this->Continent));
		$this->assertInstanceOf('Continent', $this->Continent);
	}

	//TODO
}

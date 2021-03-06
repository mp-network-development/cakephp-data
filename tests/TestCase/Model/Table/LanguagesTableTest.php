<?php

namespace Data\Test\TestCase\Model;

use Cake\ORM\TableRegistry;
use Tools\TestSuite\TestCase;

class LanguagesTableTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.data.languages'
	];

	/**
	 * @var \Data\Model\Table\LanguagesTable
	 */
	public $Languages;

	public function setUp() {
		parent::setUp();

		$this->Languages = TableRegistry::get('Data.Languages');
	}

	public function testObject() {
		$this->assertTrue(is_object($this->Languages));
		$this->assertInstanceOf('\Data\Model\Table\LanguagesTable', $this->Languages);
	}

	//TODO

}

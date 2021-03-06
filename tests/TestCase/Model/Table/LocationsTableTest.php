<?php

namespace Data\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Tools\TestSuite\TestCase;

class LocationsTableTest extends TestCase {

	/**
	 * @var array
	 */
	public $fixtures = [
		'plugin.data.locations'
	];

	/**
	 * @var \Data\Model\Table\LocationsTable
	 */
	public $Locations;

	/**
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->Locations = TableRegistry::get('Data.Locations');
	}

	/**
	 * @return void
	 */
	public function testBasicFind() {
		$result = $this->Locations->find()->first();
		$this->assertNotEmpty($result);
	}

	/**
	 * @return void
	 */
	public function testBasicSave() {
		$data = [
			'country_id' => 1,
			'state_id' => 1,
			'city' => 'Berlin',
		];
		$location = $this->Locations->newEntity($data);
		$result = $this->Locations->save($location);
		$this->assertNotEmpty($result);
	}

}

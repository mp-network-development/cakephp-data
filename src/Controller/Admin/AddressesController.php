<?php
namespace Data\Controller\Admin;

use Cake\Core\Configure;
use Data\Controller\DataAppController;

/**
 * @property \Data\Model\Table\AddressesTable $Addresses
 */
class AddressesController extends DataAppController {

	/**
	 * @return void
	 */
	public function index() {
		$addresses = $this->paginate();
		$this->set(compact('addresses'));
		$this->helpers[] = 'Geo.GoogleMap';
	}

	/**
	 * @param int|null $id
	 * @return \Cake\Http\Response|null
	 */
	public function view($id = null) {
		if (empty($id) || !($address = $this->Addresses->find('first', ['conditions' => ['Addresses.id' => $id]]))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(['action' => 'index']);
		}
		$this->set(compact('address'));
		$this->helpers[] = 'Geo.GoogleMap';
	}

	/**
	 * @return \Cake\Http\Response|null
	 */
	public function add() {
		$address = $this->Addresses->newEntity();
		if ($this->Common->isPosted()) {
			$address = $this->Addresses->patchEntity($address, $this->request->data);
			if ($this->Addresses->save($address)) {
				$var = $address['formatted_address'];
				$this->Flash->success(__('record add {0} saved', h($var)));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('formContainsErrors'));

		} else {
			# TODO: geolocate via IP? only for frontend
			$options = ['Countries.iso2' => 'DE'];
			$this->request->data['country_id'] = $this->Addresses->Countries->fieldByConditions('id', $options);
		}

		$countries = $this->Addresses->Countries->find('list');
		$countryProvinces = [];
		if (Configure::read('Data.Address.State')) {
			$countryProvinces = $this->Addresses->States->find('list');
		}

		$this->set(compact('countries', 'countryProvinces'));
	}

	public function edit($id = null) {
		$address = $this->Addresses->get($id);
		if ($this->Common->isPosted()) {
			$address = $this->Addresses->patchEntity($address, $this->request->data);

			if ($this->Addresses->save($address)) {
				$var = $address['formatted_address'];
				$this->Flash->success(__('record edit {0} saved', h($var)));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('formContainsErrors'));

		}
		if (empty($this->request->data)) {
			//$this->request->data = $address;
			$belongsTo = ['' => ' - keine Auswahl - '];
			foreach ($this->Addresses->belongsTo as $b => $content) {
				if ($b === 'Country') {
					continue;
				}
				$belongsTo[$b] = $b;
			}
			if (!empty($belongsTo)) {
				$this->set('models', $belongsTo);
			}
		}
		$countries = $this->Addresses->Countries->find('list');
		$states = [];
		if (Configure::read('Data.Address.State')) {
			$states = $this->Addresses->States->find('list');
		}

		$this->set(compact('countries', 'states'));
	}

	/**
	 * @param int|null $id
	 * @return \Cake\Http\Response|null
	 */
	public function delete($id = null) {
		$this->request->allowMethod('post');

		$address = $this->Addresses->get($id);
		$var = $address['formatted_address'];

		if ($this->Addresses->delete($address)) {
			$this->Flash->success(__('record del {0} done', h($var)));
			return $this->redirect(['action' => 'index']);
		}
		$this->Flash->error(__('record del {0} not done exception', h($var)));
		return $this->Common->autoRedirect(['action' => 'index']);
	}

	public function markAsUsed($id = null) {
		if (empty($id) || !($address = $this->Addresses->find('first', ['conditions' => ['Address.id' => $id], 'fields' => ['id', 'formatted_address']]))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(['action' => 'index']);
		}
		$this->Addresses->touch($id);
		$var = $address['formatted_address'];
		$this->Flash->success(__('Address \'{0}\' marked as last used', h($var)));
		return $this->Common->autoRedirect(['action' => 'index']);
	}

}

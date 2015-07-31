<?php
namespace Data\Controller\Admin;

use Data\Controller\DataAppController;

class SmileysController extends DataAppController {

	public $paginate = array('order' => array('Smiley.is_base' => 'DESC', 'Smiley.sort' => 'ASC'), 'limit' => 100);

	public function index() {
		$smileys = $this->paginate();
		$this->set(compact('smileys'));
	}

	public function view($id = null) {
		if (empty($id) || !($smiley = $this->Smiley->find('first', array('conditions' => array('Smiley.id' => $id))))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(array('action' => 'index'));
		}
		$this->set(compact('smiley'));
	}

	public function add() {
		if ($this->Common->isPosted()) {
			$this->Smiley->create();
			if ($this->Smiley->save($this->request->data)) {
				$var = $this->Smiley->id; //$this->request->data['Smiley']['name'];
				$this->Flash->success(__('record add {0} saved', h($var)));
				return $this->Common->autoRedirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('formContainsErrors'));
			}
		}
	}

	public function edit($id = null) {
		if (empty($id) || !($smiley = $this->Smiley->find('first', array('conditions' => array('Smiley.id' => $id))))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(array('action' => 'index'));
		}
		if ($this->Common->isPosted()) {
			if ($this->Smiley->save($this->request->data)) {
				$var = $id; //$this->request->data['Smiley']['name'];
				$this->Flash->success(__('record edit {0} saved', h($var)));
				return $this->Common->postRedirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('formContainsErrors'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $smiley;
		}
	}

	public function delete($id = null) {
		if (!$this->Common->isPosted()) {
			throw new MethodNotAllowedException();
		}
		if (empty($id) || !($smiley = $this->Smiley->find('first', array('conditions' => array('Smiley.id' => $id), 'fields' => array('id'))))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(array('action' => 'index'));
		}
		if ($this->Smiley->delete($id)) {
			$var = $smiley['Smiley']['id'];
			$this->Flash->success(__('record del {0} done', h($var)));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Flash->error(__('record del {0} not done exception', h($var)));
		return $this->Common->autoRedirect(array('action' => 'index'));
	}

	/**
	 * Toggle - ajax
	 */
	public function toggle($field = null, $id = null) {
		 $fields = array('active');

		if (!empty($field) && in_array($field, $fields) && !empty($id)) {
			$value = $this->{$this->modelClass}->toggleField($field, $id);
		}
		$model = $this->{$this->modelClass}->alias;

		if (!$this->request->is('ajax')) {
			return $this->Common->autoRedirect(array('action' => 'index'));
		}
		$this->autoRender = false;
		if (isset($value)) {
			$this->set('ajaxToggle', $value);
			$this->set(compact('field', 'model'));

			$this->render('admin_toggle', 'ajax');
		}
	}

/****************************************************************************************
 * protected/interal functions
 ****************************************************************************************/

/****************************************************************************************
 * deprecated/test functions
 ****************************************************************************************/

}

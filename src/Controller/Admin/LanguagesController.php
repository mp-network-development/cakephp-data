<?php
namespace Data\Controller\Admin;

use Cake\Core\Plugin;
use Data\Controller\DataAppController;
use Data\Model\Entity\Language;

class LanguagesController extends DataAppController {

	public $paginate = array('order' => array('Language.name' => 'ASC'));

	/**
	 * LanguagesController::admin_index()
	 *
	 * @return void
	 */
	public function index() {
		if (Plugin::loaded('Search')) {
			$this->Languages->addBehavior('Search.Searchable');
			$this->Common->loadComponent('Search.Prg');

			$this->Prg->commonProcess();
			$languages = $this->paginate($this->Languages->find('searchable', $this->Prg->parsedParams()));
		}

		$language = $this->Languages->newEntity();
		$languages = $this->paginate();
		$this->set(compact('languages', 'language'));
	}

	public function view($id = null) {
		if (empty($id) || !($language = $this->Languages->find('first', array('conditions' => array('Language.id' => $id))))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(array('action' => 'index'));
		}
		$this->set(compact('language'));
	}

	public function add() {
		$language = $this->Languages->newEntity();
		
		if ($this->Common->isPosted()) {
			//$this->Languages->create();
			$language = $this->Languages->patchEntity($language, $this->request->data);
			if ($this->Languages->save($language)) {
				$var = $this->request->data['name'];
				$this->Flash->success(__('record add {0} saved', h($var)));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('formContainsErrors'));
			}
		}
		
		$this->set(compact('language'));
	}

	public function edit($id = null) {
		if (empty($id) || !($language = $this->Languages->find('first', array('conditions' => array('Language.id' => $id))))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(array('action' => 'index'));
		}
		if ($this->Common->isPosted()) {
			if ($this->Languages->save($this->request->data)) {
				$var = $this->request->data['name'];
				$this->Flash->success(__('record edit {0} saved', h($var)));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Flash->error(__('formContainsErrors'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $language;
		}
		
		$this->set(compact('language'));
	}

	public function delete($id = null) {
		if (!$this->Common->isPosted()) {
			throw new MethodNotAllowedException();
		}
		if (empty($id) || !($language = $this->Languages->find('first', array('conditions' => array('Language.id' => $id), 'fields' => array('id', 'name'))))) {
			$this->Flash->error(__('invalid record'));
			return $this->Common->autoRedirect(array('action' => 'index'));
		}
		if ($this->Languages->delete($id)) {
			$var = $language['Language']['name'];
			$this->Flash->success(__('record del {0} done', h($var)));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Flash->error(__('record del {0} not done exception', h($var)));
		return $this->Common->autoRedirect(array('action' => 'index'));
	}

	/**
	 * Should only be done once at the very beginning
	 */
	public function import_from_core() {
		if (!empty($this->request->params['named']['reset'])) {
			$this->Languages->truncate();
		}
		//$languages = $this->Languages->iso3ToIso2();
		$languages = $this->Languages->catalog();
		$count = 0;
		$errors = array();
		foreach ($languages as $language) {
			if (!($code = $this->Languages->iso3ToIso2($language['localeFallback']))) {
				$code = '';
			}
			$data = array(
				'name' => $language['language'],
				'ori_name' => $language['language'],
				'code' => $code,
				'locale' => $language['locale'],
				'locale_fallback' => $language['localeFallback'],
				'direction' => $language['direction']
			);
			$this->Languages->create();
			if ($this->Languages->save($data)) {
				$count++;
			} else {
				$errors[] = array('data' => $language, 'errors' => $this->Languages->validationErrors);
			}
		}

		$this->Flash->success($count . ' of ' . count($languages) . ' ' . __('languages added'));

		$errorMessage = array();
		foreach ($errors as $error) {
			$errorMessage[] = $error['data']['language'] . ' (' . returns($error['errors']) . ')';
		}
		$this->Flash->warning(__('not added') . ' ' . implode(', ', $errorMessage));
		return $this->redirect(array('action' => 'index'));
		//pr($errors);
	}

	/**
	 * http://www.loc.gov/standards/iso639-2/php/code_list.php
	 */
	public function compare_to_iso_list() {
		$isoList = $this->Languages->getOfficialIsoList();

		$languages = $this->Languages->find('all', array());

		$this->set(compact('isoList', 'languages'));
	}

	/**
	 * http://www.loc.gov/standards/iso639-2/php/code_list.php
	 */
	public function compare_iso_list_to_core() {
		$isoList = $this->Languages->getOfficialIsoList();

		$languages = $this->Languages->catalog();
		$locales = array();
		foreach ($languages as $key => $value) {
			if (strlen($key) === 2) {
				$locales[$key] = $value;
				$locales[$key]['regional'] = array();
				continue;
			}
			if (strlen($key) === 1) {
				//$locales[$key] = $value;
				//$locales[$key]['deprecated'] = 1;
				continue;
			}
			$baseLocale = substr($key, 0, 2);
			if (!isset($locales[$baseLocale])) {
				$locales[$baseLocale] = array('missing_base' => 1);
			}
			$locales[$baseLocale]['regional'][] = $value;
		}

		//die(debug($locales));

		$this->set(compact('isoList', 'languages', 'locales'));
	}

	public function set_primary_languages_active() {
		$languages = $this->Languages->getPrimaryLanguages('list');
		$this->Languages->updateAll(array('status' => Language::STATUS_ACTIVE), array('id' => array_keys($languages)));

		$this->Flash->success(__('{0} of {1} set active', $this->Languages->getAffectedRows(), count($languages)));
		return $this->redirect(array('action' => 'index'));
	}

/* probs:

Array
(
	[0] => Array
		(
			[data] => Array
				(
					[language] => Greek
					[locale] => gre
					[localeFallback] => gre
					[charset] => utf-8
					[direction] => ltr
				)

			[errors] => Array
				(
					[locale] => valErrRecordNameExists
				)

		)

	[1] => Array
		(
			[data] => Array
				(
					[language] => Indonesian
					[locale] => ind
					[localeFallback] => ind
					[charset] => utf-8
					[direction] => ltr
				)

			[errors] => Array
				(
					[locale] => valErrRecordNameExists
				)

		)

	[2] => Array
		(
			[data] => Array
				(
					[language] => Dutch (Standard)
					[locale] => dut
					[localeFallback] => dut
					[charset] => utf-8
					[direction] => ltr
				)

			[errors] => Array
				(
					[locale] => valErrRecordNameExists
				)

		)

	[3] => Array
		(
			[data] => Array
				(
					[language] => Polish
					[locale] => pol
					[localeFallback] => pol
					[charset] => utf-8
					[direction] => ltr
				)

			[errors] => Array
				(
					[locale] => valErrRecordNameExists
				)

		)

)

*/

}

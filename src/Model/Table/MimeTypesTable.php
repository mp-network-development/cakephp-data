<?php
namespace Data\Model\Table;

use ArrayObject;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Tools\Mailer\Email;
use Tools\Model\Table\Table;

/**
 * @property \Data\Model\Table\MimeTypeImagesTable|\Cake\ORM\Association\BelongsTo $MimeTypeImages
 */
class MimeTypesTable extends Table {

	/**
	 * @var array
	 */
	public $order = ['modified' => 'DESC'];

	/**
	 * @var array
	 */
	public $validate = [
		'name' => [
			'notBlank' => [
				'rule' => ['notBlank'],
				'message' => 'valErrMandatoryField',
			],
		],
		'ext' => [
			'isUnique' => [
				'rule' => 'isUnique',
				'message' => 'Already exists',
			],
			'notBlank' => [
				'rule' => ['notBlank'],
				'message' => 'valErrMandatoryField',
				'allowEmpty' => false,
				//'required' => true
			],
		],
		'type' => [
			'notBlank' => [
				'rule' => ['notBlank'],
				'message' => 'valErrMandatoryField',
			],
		],
		'active' => ['numeric'],
		'mime_type_image_id' => ['numeric'],
	];

	/**
	 * @var array
	 */
	public $belongsTo = [
		'MimeTypeImage' => [
			'className' => 'Data.MimeTypeImage',
			'foreignKey' => 'mime_type_image_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		],
	];

	/**
	 * @param \Cake\Event\Event $event
	 * @param \Cake\ORM\Entity $entity
	 *
	 * @return bool
	 */
	public function beforeSave(Event $event, Entity $entity) {
		if (isset($entity['ext'])) {
			$entity['ext'] = mb_strtolower($entity['ext']);
		}
		if (isset($entity['name'])) {
			$entity['name'] = ucwords($entity['name']);
		}
	}

	public function _afterSave($created, $options = []) {
		$this->cleanUp();
	}

	/**
	 * @param \Cake\Event\Event $event
	 * @param \Cake\ORM\Entity $entity
	 * @param \ArrayObject $options
	 * @return void
	 */
	public function afterDelete(Event $event, Entity $entity, ArrayObject $options) {
		$this->cleanUp();
	}

	public function cleanUp() {
		//$Handle = new File(FILES . 'mime_types.txt');
		//$Handle->delete();
	}

	/**
	 * @param bool $inactiveOnes
	 *
	 * @return \Cake\ORM\Query
	 */
	public function mimeTypes($inactiveOnes = false) {
		$options = ['conditions' => []];
		if ($inactiveOnes !== true) {
			$options['conditions'][$this->alias() . '.active'] = 1;
		}
		return $this->find('all', $options);
	}

	/**
	 * @param string|null $ext
	 *
	 * @return \Cake\ORM\Query|null
	 */
	public function findMimeType($ext = null) {
		if (empty($ext)) {
			return null;
		}
		return $this->find('all', ['conditions' => [$this->alias() . '.ext' => $ext]])->first();
	}

	/**
	 * Push this type up in the "usage ranking" (sort)
	 * could be done on every upload/download = automagic sort by priority
	 *
	 * @param string|null $ext
	 * @return bool
	 */
	public function push($ext = null) {
		$type = $this->findMimeType($ext);
		if ($type) {
			$id = $type['id'];
			return $this->saveField($id, 'sort', $type['sort'] + 1);
		}
		# insert this new extension
		$data = ['ext' => $ext, 'name' => 'auto-added', 'sort' => 1];
		//$this->create();
		$mimeType = $this->newEntity($data);
		if (!$this->save($mimeType)) {
			//$this->log('problem with pushing new mimeType');
			return false;
		}
		# notify admin
		$this->Email = new Email();
		$this->Email->to(Configure::read('Config.adminEmail'), Configure::read('Config.adminEmailname'));
		$this->Email->replyTo(Configure::read('Config.adminEmail'), Configure::read('Config.adminEmailname'));

		$this->Email->subject(Configure::read('Config.page_name') . ' - ' . __('MimeType'));
		$this->Email->template('simple_email');

		$text = 'MimeType hinzugefügt: ' . $ext . '';
		$this->Email->viewVars(compact('text'));

		if (!$this->Email->send()) {
			//$this->log('problem with mailing to admin after pushing mimeType');
		}

		return true;
	}

}

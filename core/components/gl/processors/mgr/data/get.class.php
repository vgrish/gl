<?php

/**
 * Get an glData
 */
class modglDataGetProcessor extends modObjectGetProcessor
{
	public $objectType = 'glData';
	public $classKey = 'glData';
	public $languageTopics = array('gl');
	public $permission = '';

	public $gl;

	/** {@inheritDoc} */
	public function initialize()
	{
		/** @var gl $gl */
		$this->gl = $this->modx->getService('gl');
		$this->gl->initialize($this->getProperty('context', $this->modx->context->key));

		return parent::initialize();
	}

	/**
	 * @return array|string
	 */
	public function cleanup()
	{
		$array = $this->object->toArray();

		$class = $array['class'];
		$identifier = $array['identifier'];
		if (
			!empty($class) AND
			$object = $this->modx->getObject($class, $identifier)
		) {
			$array['active'] = $object->get('active');
		}

		return $this->success('', $array);
	}

}

return 'modglDataGetProcessor';
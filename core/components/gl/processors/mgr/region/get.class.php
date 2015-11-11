<?php

/**
 * Get an glRegion
 */
class modglRegionGetProcessor extends modObjectGetProcessor
{
	public $objectType = 'glRegion';
	public $classKey = 'glRegion';
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
		$set = $this->object->toArray();

		/*$process = $this->getProperty('process', false);
		if ($process) {
			$set = array_merge($this->gl->Tools->processObject($this->object, true, true, '', true), $set);
		}

		$aliases = $this->modx->fromJSON($this->getProperty('aliases', ''));
		if (!empty($aliases)) {
			foreach ($aliases as $alias) {
				$keyPrefix = '';
				if (in_array($alias, array('ParentUser', 'ParentUserProfile'))) {
					$keyPrefix = 'parent_';
				}
				if ($o = $this->object->getOne($alias)) {
					$set = array_merge($this->gl->Tools->processObject($o, true, true, $keyPrefix, true), $set);
				}
			}
		}*/

		return $this->success('', $set);
	}

}

return 'modglRegionGetProcessor';
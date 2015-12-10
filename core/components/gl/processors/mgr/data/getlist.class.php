<?php

/**
 * Get a list of glData
 */
class modglDataGetListProcessor extends modObjectGetListProcessor
{
	public $objectType = 'glData';
	public $classKey = 'glData';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'ASC';
	public $languageTopics = array('default', 'gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeQuery()
	{
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}

	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c)
	{

		if (!$this->getProperty('combo')) {

		} else {

		}

		$c->groupby('glData.id');

		$c->leftJoin('glCountry', 'glCountry', 'glCountry.id = glData.identifier');
		$c->leftJoin('glRegion', 'glRegion', 'glRegion.id = glData.identifier');
		$c->leftJoin('glCity', 'glCity', 'glCity.id = glData.identifier');

		$c->select($this->modx->getSelectColumns('glData', 'glData'));
		$c->select(array(
			'name' => 'glCountry.name_ru',
			'name1' => 'glRegion.name_ru',
			'name2' => 'glCity.name_ru',

			'active' => 'glCountry.active',
			'active1' => 'glRegion.active',
			'active2' => 'glCity.active',
		));

		$query = trim($this->getProperty('query'));
		if ($query) {
			$c->where(array(
				'name_ru:LIKE' => "%{$query}%",
				'OR:name_en:LIKE' => "%{$query}%",
			));
		}

//		$c->sortby('active', 'DESC');
//		$c->sortby('name_ru', 'ASC');

		return $c;
	}

	/** {@inheritDoc} */
	public function outputArray(array $array, $count = false)
	{
		if ($this->getProperty('addall')) {
			$array = array_merge_recursive(array(array(
				'id' => 0,
				'name' => $this->modx->lexicon('gl_all')
			)), $array);
		}
		return parent::outputArray($array, $count);
	}

	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object)
	{
		$icon = 'fa';
		$array = $object->toArray();
		$array['actions'] = array();

		// Edit
		$array['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-edit green",
			'title' => $this->modx->lexicon('gl_action_update'),
			'action' => 'update',
			'button' => true,
			'menu' => true,
		);

		if ($array['default']) {
			return $array;
		}

		switch (true) {
			case $array['active']:
				break;
			case $array['active1']:
				$array['active'] = 1;
				break;
			case $array['active2']:
				$array['active'] = 1;
				break;
			default:
				$array['active'] = 0;
		}

		if (!$array['active']) {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => "$icon $icon-toggle-off red",
				'title' => $this->modx->lexicon('gl_action_active'),
				'action' => 'active',
				'button' => true,
				'menu' => true,
			);
		} else {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => "$icon $icon-toggle-on green",
				'title' => $this->modx->lexicon('gl_action_inactive'),
				'action' => 'inactive',
				'button' => true,
				'menu' => true,
			);
		}
		// sep
		$array['actions'][] = array(
			'cls' => '',
			'icon' => '',
			'title' => '',
			'action' => 'sep',
			'button' => false,
			'menu' => true,
		);
		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-trash-o red",
			'title' => $this->modx->lexicon('gl_action_remove'),
			'action' => 'remove',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'modglDataGetListProcessor';
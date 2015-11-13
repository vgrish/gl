<?php


class modglClassGetListProcessor extends modObjectProcessor
{
	public $languageTopics = array('gl');

	/** {@inheritDoc} */
	public function process()
	{
		$array = array(
			0 => array(
				'name' => $this->modx->lexicon('gl_default'),
				'value' => ''
			),
			1 => array(
				'name' => $this->modx->lexicon('gl_city'),
				'value' => 'glCity'
			),
			2 => array(
				'name' => $this->modx->lexicon('gl_region'),
				'value' => 'glRegion'
			),
			3 => array(
				'name' => $this->modx->lexicon('gl_country'),
				'value' => 'glCountry'
			),
		);

		$query = $this->getProperty('query');
		if (!empty($query)) {
			foreach($array as $k => $format) {
				if (stripos($format['name'], $query) === FALSE ) {
					unset($array[$k]);
				}
			}
			sort($array);
		}

		return $this->outputArray($array);
	}

	/** {@inheritDoc} */
	public function outputArray(array $array, $count = false)
	{
		if ($this->getProperty('addall')) {
			$array = array_merge_recursive(array(array(
				'name' => $this->modx->lexicon('gl_all'),
				'value' => ''
			)), $array);
		}
		return parent::outputArray($array, $count);
	}

}

return 'modglClassGetListProcessor';
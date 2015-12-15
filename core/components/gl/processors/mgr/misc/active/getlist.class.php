<?php


class modglActiveGetListProcessor extends modObjectProcessor
{
	public $languageTopics = array('gl');

	/** {@inheritDoc} */
	public function process()
	{
		$array = array(
			0 => array(
				'name' => $this->modx->lexicon('gl_active'),
				'value' => '1'
			),
			1 => array(
				'name' => $this->modx->lexicon('gl_inactive'),
				'value' => '0'
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

return 'modglActiveGetListProcessor';
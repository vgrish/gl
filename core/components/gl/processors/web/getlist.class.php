<?php

/**
 * Get a list of glLocation
 */
class modglLocationGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = '';
    public $classKey = '';
    public $defaultSortField = 'name_ru';
    public $defaultSortDirection = 'ASC';
    public $languageTopics = array('default', 'gl');
    public $permission = '';

    /** {@inheritDoc} */
    public function initialize()
    {
        switch ($this->getProperty('class')) {
            case 'glCountry':
                $this->objectType = $this->classKey = 'glCountry';
                break;
            case 'glRegion':
                $this->objectType = $this->classKey = 'glRegion';
                break;
            case 'glCity':
                $this->objectType = $this->classKey = 'glCity';
                break;
        }
        if (empty($this->classKey)) {
            return $this->modx->lexicon('gl_err_class_ns');
        }

        return parent::initialize();
    }

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
        $c->leftJoin("glData", "glData", "glData.identifier = {$this->classKey}.id");

        $c->select($this->modx->getSelectColumns("glData", "glData"));
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));

        switch ($this->classKey) {
            case 'glCountry':
                break;
            case 'glRegion':
                $c->leftJoin("glCountry", "glCountry", "glCountry.iso = {$this->classKey}.country");
                $c->select($this->modx->getSelectColumns("glCountry", "glCountry", 'country_', array('id'), true));
                break;
            case 'glCity':
                $c->leftJoin("glRegion", "glRegion", "glRegion.id = {$this->classKey}.region_id");
                $c->select($this->modx->getSelectColumns("glRegion", "glRegion", 'region_', array('id'), true));

                $c->leftJoin("glCountry", "glCountry", "glCountry.iso = glRegion.country");
                $c->select($this->modx->getSelectColumns("glCountry", "glCountry", 'country_', array('id'), true));
                break;
        }

        $active = $this->getProperty('active');
        if ($active != '') {
            $c->where(array("{$this->objectType}.active" => $active));
        }

        $default = $this->getProperty('default');
        if ($default != '') {
            $c->where(array("{$this->objectType}.default" => $default));
        }

        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where(array(
                "{$this->objectType}.name_ru:LIKE"    => "%{$query}%",
                "OR:{$this->objectType}.name_en:LIKE" => "%{$query}%",
            ));
        }

        return $c;
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        return parent::outputArray($array, $count);
    }

    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $array = $object->toArray();

        if (empty($array['class'])) {
            $array['class'] = $this->objectType;
        }

        return $array;
    }

}

return 'modglLocationGetListProcessor';
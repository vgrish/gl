<?php

/**
 * Get a list of glCity
 */
class modglCityGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'glCity';
    public $classKey = 'glCity';
    public $defaultSortField = 'name_ru';
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
        $c->leftJoin('glRegion', 'glRegion', 'glRegion.id = glCity.region_id');
        $c->select($this->modx->getSelectColumns('glCity', 'glCity'));
        $c->select(array(
            'region_name_ru' => 'glRegion.name_ru',
        ));

        $id = $this->getProperty('id');
        if (!empty($id) AND $this->getProperty('combo')) {
            $q = $this->modx->newQuery($this->objectType);
            $q->where(array('id!=' => $id));
            $q->select('id');
            $q->limit(11);
            $q->prepare();
            $q->stmt->execute();
            $ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            $ids = array_merge_recursive(array($id), $ids);
            $c->where(array(
                "{$this->objectType}.id:IN" => $ids
            ));
        }

        $regionId = $this->getProperty('region_id');
        if ($regionId != '') {
            $c->where("{$this->objectType}.region_id={$regionId}");
        }

        $active = $this->getProperty('active');
        if ($active != '') {
            $c->where("{$this->objectType}.active={$active}");
        }

        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->where(array(
                "{$this->objectType}.name_ru:LIKE"    => "%{$query}%",
                "OR:{$this->objectType}.name_en:LIKE" => "%{$query}%",
            ));
        }

        $c->sortby('glCity.active', 'DESC');
        $c->sortby('glCity.name_ru', 'ASC');

        return $c;
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive(array(
                array(
                    'id'   => 0,
                    'name' => $this->modx->lexicon('gl_all')
                )
            ), $array);
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
        $icon = 'icon';
        $array = $object->toArray();

        if ($this->getProperty('combo', false)) {
            return $array;
        }

        $array['actions'] = array();

        // Edit
        /*$array['actions'][] = array(
            'cls' => '',
            'icon' => "$icon $icon-eye green",
            'title' => $this->modx->lexicon('gl_action_view'),
            'action' => 'update',
            'button' => true,
            'menu' => true,
        );*/
        if (!$array['active']) {
            $array['actions'][] = array(
                'cls'    => '',
                'icon'   => "$icon $icon-toggle-off red",
                'title'  => $this->modx->lexicon('gl_action_active'),
                'action' => 'active',
                'button' => true,
                'menu'   => true,
            );
        } else {
            $array['actions'][] = array(
                'cls'    => '',
                'icon'   => "$icon $icon-toggle-on green",
                'title'  => $this->modx->lexicon('gl_action_inactive'),
                'action' => 'inactive',
                'button' => true,
                'menu'   => true,
            );
        }
        // sep
        $array['actions'][] = array(
            'cls'    => '',
            'icon'   => '',
            'title'  => '',
            'action' => 'sep',
            'button' => false,
            'menu'   => true,
        );
        // Remove
        $array['actions'][] = array(
            'cls'    => '',
            'icon'   => "$icon $icon-trash-o red",
            'title'  => $this->modx->lexicon('gl_action_remove'),
            'action' => 'remove',
            'button' => true,
            'menu'   => true,
        );

        return $array;
    }

}

return 'modglCityGetListProcessor';
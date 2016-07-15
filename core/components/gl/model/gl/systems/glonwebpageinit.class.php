<?php

class glOnWebPageInit extends glPlugin
{
    public function run()
    {
        if (empty($this->gl->opts['set'])) {
            $q = $this->modx->newQuery('glData', array('resource' => $this->modx->resourceIdentifier));
            $q->select('identifier, class');
            $q->prepare();
            $q->stmt->execute();
            $data = end($q->stmt->fetchAll(PDO::FETCH_ASSOC));

            if (!empty($data)) {
                $current = $this->gl->getCurrentData($data['identifier'], $data['class']);
                if (!empty($current)) {
                    $this->gl->opts['current'] = $current;
                }
            }
        }

        switch(true) {
            case empty($this->gl->opts['set']) AND empty($this->gl->opts['current']):
                $this->gl->opts['selected'] = $this->gl->opts['real'];
                break;
            case empty($this->gl->opts['set']) AND !empty($this->gl->opts['current']):
                $this->gl->opts['selected'] = $this->gl->opts['current'];
                break;
            case !empty($this->gl->opts['set']):
                $this->gl->opts['selected'] = $this->gl->opts['current'];
                break;
        }


        $this->gl->setPlaceholders((array)$this->gl->opts);
    }


}

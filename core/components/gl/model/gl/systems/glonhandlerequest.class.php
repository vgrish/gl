<?php

class glOnHandleRequest extends glPlugin
{
    public function run()
    {
        /* check context */
        if ($this->modx->context->key == 'mgr') {
            return;
        }
        /* check site status */
        if (
            $this->modx->getOption('gl_ischeck_site_status', null, true, true)
            AND
            !$this->modx->checkSiteStatus()
        ) {
            return;
        }

        $this->gl->initialize($this->modx->context->key);

        if (empty($this->gl->opts['check'])) {
            $this->gl->opts['real'] = $this->gl->getRealData();
            if ($this->gl->opts['real']['country']['id']) {
                $this->gl->opts['current'] = $this->gl->opts['real'];
            } else {
                $this->gl->opts['current'] = $this->gl->getDefaultData();
            }
            $this->gl->opts['check'] = true;
        }

        switch (true) {
            case empty($this->gl->opts['set']) AND $this->modx->getOption('gl_selected_is_real', null, false, true):
                $this->gl->opts['selected'] = $this->gl->opts['real'];
                break;
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

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
        if ($this->modx->getOption('gl_ischeck_site_status', null, true, true) AND !$this->modx->checkSiteStatus()) {
            return;
        }

        $this->gl->initialize($this->modx->context->key);

        if (empty($this->gl->opts['check'])) {
            $this->gl->opts['real'] = $this->gl->getRealData();

            if (!isset($this->gl->opts['current'])) {
                $this->gl->opts['current'] = $this->gl->getDefaultData();
            }

            $this->gl->opts['check'] = true;
        }
    }

}

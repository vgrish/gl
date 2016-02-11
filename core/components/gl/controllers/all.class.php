<?php

/**
 * The home manager controller for gl.
 *
 */
class glAllManagerController extends glMainController
{
    /* @var gl $gl */
    public $gl;


    /**
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = array())
    {
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('gl');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/util/datetime.js');

        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/misc/gl.utils.js');
        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/misc/gl.combo.js');

        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/country/country.grid.js');
        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/country/country.window.js');

        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/region/region.grid.js');

        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/city/city.grid.js');

        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/data/data.grid.js');
        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/data/data.window.js');

        $this->addJavascript($this->gl->config['jsUrl'] . 'mgr/all/all.panel.js');

        $script = 'Ext.onReady(function() {
			MODx.add({ xtype: "gl-panel-all"});
		});';
        $this->addHtml("<script type='text/javascript'>{$script}</script>");

    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->gl->config['templatesPath'] . 'all.tpl';
    }
}
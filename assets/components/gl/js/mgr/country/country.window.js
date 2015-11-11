gl.window.CreateCountry = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('create'),
        width: 650,
        autoHeight: true,
        url: gl.config.connector_url,
        action: 'mgr/country/create',
        fields: this.getFields(config),
		keys: this.getKeys(config),
		buttons: this.getButtons(config),
		fileUpload: config.fileUpload || false
    });
    gl.window.CreateCountry.superclass.constructor.call(this, config);
};
Ext.extend(gl.window.CreateCountry, MODx.Window, {

	getKeys: function (config) {
		return [{
			key: Ext.EventObject.ENTER,
			shift: true,
			fn: this.submit,
			scope: this
		}];
	},

	getButtons: function (config) {
		return [{
			text: !config.update ? _('create') : _('save'),
			scope: this,
			handler: function () {
				this.submit();
			}
		}];
	},

    getFields: function(config) {
        return [{
            xtype: 'hidden',
            name: 'id'
        }, {
            xtype: 'modx-tabs',
            defaults: {
                border: false,
                autoHeight: true
            },
            border: true,
            activeTab: 0,
            autoHeight: true,
            items: this.getTabs(config)
        }]
    },

    getTabs: function (config) {

        var tabs = [];
        var add = {
            host: {
                layout: 'form',
                items: this.getHost(config)
            },
            log: {
                layout: 'form',
                items: this.getHostLog(config),
                disabled: !config.update
            }
        };

        for (var i = 0; i < gl.config.host_window_tabs.length; i++) {
            var tab = gl.config.host_window_tabs[i];
            if (add[tab]) {
                Ext.applyIf(add[tab], {
                    title: _('gl_tab_' + tab)
                });
                tabs.push(add[tab]);
            }
        }

        return tabs;
    },

    getHost: function(config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('gl_host'),
            name: 'host',
            anchor: '99%',
            allowBlank: false
        }, {
            xtype: 'xcheckbox',
            hideLabel: true,
            boxLabel: _('gl_description'),
            checked: false,
            workCount: 1,
            listeners: {
                check: gl.utils.handleChecked,
                afterrender: gl.utils.handleChecked
            }
        }, {
            xtype: 'textarea',
            fieldLabel: _('gl_description'),
            name: 'description',
            anchor: '99%',
            allowBlank: true
        }, {
            xtype: 'xcheckbox',
            hideLabel: true,
            boxLabel: _('gl_properties'),
            checked: false,
            workCount: 1,
            listeners: {
                check: gl.utils.handleChecked,
                afterrender: gl.utils.handleChecked
            }
        }, {
            xtype: 'textarea',
            fieldLabel: _('gl_properties'),
            name: 'properties',
            anchor: '99%',
            allowBlank: true
        }, {
            xtype: 'checkboxgroup',
            hideLabel: true,
            /*fieldLabel: '',*/
            columns: 3,
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('gl_active'),
                name: 'active',
                checked: config.record.active
            }]
        }];
    },

    getHostLog: function(config) {
        return [{
            items: {
                xtype: 'gl-grid-country-log',
                compact: true,
                host: config.record.id
            }
        }];
    },

    getLeftFields: function(config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('gl_package'),
            name: 'package',
            anchor: '99%',
            allowBlank: false,
            disabled: true
        }];
    },

    getRightFields: function(config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('gl_version'),
            name: 'version',
            anchor: '99%',
            allowBlank: false,
            disabled: true
        }];
    }

});
Ext.reg('gl-window-create-country', gl.window.CreateCountry);


gl.window.CreateData = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('create'),
        width: 650,
        autoHeight: true,
        url: gl.config.connector_url,
        action: 'mgr/data/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    gl.window.CreateData.superclass.constructor.call(this, config);

    if (!config.update) {
        config.update = false;
    }

    this.on('afterrender', function () {
        Ext.each(this.fp.getForm().items.items, function (t) {
            if (!t.name) {
                return true;
            }
            if (
                gl.config.fields_window_data.indexOf(t.name) >= 0 ||
                gl.config.fields_window_data.indexOf(t.name.replace(/(_)/, "")) >= 0
            ) {
                return true;
            }
            else {
                t.disable().hide();
            }
        });

    });

};
Ext.extend(gl.window.CreateData, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id'
        }, {
            xtype: 'textfield',
            fieldLabel: _('gl_name_alt'),
            name: 'name_alt',
            anchor: '99%',
            allowBlank: true
        }, {
            items: [{
                layout: 'form',
                cls: 'modx-panel',
                items: [{
                    layout: 'column',
                    border: false,
                    items: [{
                        columnWidth: .49,
                        border: false,
                        layout: 'form',
                        items: this.getLeftFields(config)
                    }, {
                        columnWidth: .505,
                        border: false,
                        layout: 'form',
                        cls: 'right-column',
                        items: this.getRightFields(config)
                    }]
                }]
            }]
        }, {
            xtype: 'xcheckbox',
            hideLabel: true,
            boxLabel: _('gl_address'),
            name: '_address',
            checked: false,
            listeners: {
                check: gl.utils.handleChecked,
                afterrender: gl.utils.handleChecked
            }
        }, {
            xtype: 'textarea',
            fieldLabel: '',
            msgTarget: 'under',
            name: 'address',
            anchor: '99%',
            height: 50,
            allowBlank: true
        }, {
            xtype: 'xcheckbox',
            hideLabel: true,
            boxLabel: _('gl_add'),
            name: '_add1',
            checked: false,
            listeners: {
                check: gl.utils.handleChecked,
                afterrender: gl.utils.handleChecked
            }
        }, {
            xtype: 'textarea',
            fieldLabel: '',
            msgTarget: 'under',
            name: 'add1',
            anchor: '99%',
            height: 50,
            allowBlank: true
        }, {
            xtype: 'xcheckbox',
            hideLabel: true,
            boxLabel: _('gl_add'),
            name: '_add2',
            checked: false,
            listeners: {
                check: gl.utils.handleChecked,
                afterrender: gl.utils.handleChecked
            }
        }, {
            xtype: 'textarea',
            fieldLabel: '',
            msgTarget: 'under',
            name: 'add2',
            anchor: '99%',
            height: 50,
            allowBlank: true
        }, {
            xtype: 'xcheckbox',
            hideLabel: true,
            boxLabel: _('gl_add'),
            name: '_add3',
            checked: false,
            listeners: {
                check: gl.utils.handleChecked,
                afterrender: gl.utils.handleChecked
            }
        }, {
            xtype: 'textarea',
            fieldLabel: '',
            msgTarget: 'under',
            name: 'add3',
            anchor: '99%',
            height: 50,
            allowBlank: true
        }, {
            xtype: 'checkboxgroup',
            hideLabel: true,
            /*fieldLabel: '',*/
            columns: 4,
            items: [{
                xtype: 'xcheckbox',
                boxLabel: _('gl_active'),
                name: 'active',
                checked: config.record.active
            },{
                xtype: 'xcheckbox',
                name: 'default',
                checked: false,
                hidden: true
            }]
        }];
    },

    getLeftFields: function (config) {
        return [{
            xtype: 'gl-combo-class',
            custm: true,
            clear: true,
            fieldLabel: _('gl_class'),
            hiddenName: 'class',
            name: 'class',
            anchor: '99%',
            allowBlank: false,
            listeners: {
                /*           afterrender: {
                 fn: function(r) {
                 this.handleChangeType(0);
                 },
                 scope: this
                 },*/
                select: {
                    fn: function (r) {
                        this.handleChangeType(1);
                    },
                    scope: this
                }
            }
        }, {
            xtype: 'gl-combo-resource',
            custm: true,
            clear: true,
            fieldLabel: _('gl_resource'),
            hiddenName: 'resource',
            name: 'resource',
            anchor: '99%',
            allowBlank: true
        }, {
            xtype: 'textfield',
            fieldLabel: _('gl_phone'),
            name: 'phone',
            anchor: '99%',
            allowBlank: true
        }, {
            xtype: 'textfield',
            fieldLabel: _('gl_phone'),
            name: 'phone_add',
            anchor: '99%',
            allowBlank: true
        }];
    },


    getRightFields: function (config) {
        return [{
            xtype: 'gl-combo-identifier',
            custm: true,
            clear: true,
            fieldLabel: _('gl_identifier'),
            hiddenName: 'identifier',
            name: 'identifier',
            anchor: '99%',
            allowBlank: false,
            class: config.record.class || ''
        }, {
            xtype: 'gl-combo-browser',
            custm: true,
            clear: true,
            fieldLabel: _('gl_image'),
            hiddenName: 'image',
            name: 'image',
            anchor: '99%',
            allowBlank: true
        }, {
            xtype: 'textfield',
            fieldLabel: _('gl_email'),
            name: 'email',
            anchor: '99%',
            allowBlank: true
        }, {
            xtype: 'textfield',
            fieldLabel: _('gl_email'),
            name: 'email_add',
            anchor: '99%',
            allowBlank: true
        }];
    },

    handleChangeType: function (change) {
        var f = this.fp.getForm();
        var _class = f.findField('class');
        var _identifier = f.findField('identifier');

        _identifier.baseParams.class = _class.getValue();

        if (!!_identifier.pageTb) {
            _identifier.pageTb.show();
        }
        if ((1 == change)) {
            _identifier.setValue();
        }
        _identifier.store.load();
    }

});
Ext.reg('gl-window-create-data', gl.window.CreateData);

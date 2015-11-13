gl.grid.Data = function(config) {
    config = config || {};

    this.exp = new Ext.grid.RowExpander({
        expandOnDblClick: false,
        tpl: new Ext.Template('<p class="desc">{address}</p>'),
        renderer: function(v, p, record) {
            return record.data.address != '' && record.data.address != null ? '<div class="x-grid3-row-expander">&#160;</div>' : '&#160;';
        }
    });

    this.sm = new Ext.grid.CheckboxSelectionModel();

    Ext.applyIf(config, {
        url: gl.config.connector_url,
        baseParams: {
            action: 'mgr/data/getlist',
            class: config.class || ''
        },
        save_action: 'mgr/data/updatefromgrid',
        autosave: true,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        listeners: this.getListeners(config),

        sm: this.sm,
        plugins: this.exp,

        autoHeight: true,
        paging: true,
        pageSize: 10,
        remoteSort: true,
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0
        },
        cls: 'gl-grid',
        bodyCssClass: 'grid-with-buttons',
        stateful: true,
        stateId: 'gl-grid-data-state'

    });
    gl.grid.Data.superclass.constructor.call(this, config);

};
Ext.extend(gl.grid.Data, MODx.grid.Grid, {
    windows: {},

    getFields: function(config) {
        var fields = ['id', 'identifier', 'class', 'phone', 'email', 'address', 'default', 'actions'];

        return fields;
    },

    getTopBarComponent: function(config) {
        var component = ['menu', 'create', 'left', 'active', 'search'];
        if (!!config.compact) {
            component = ['menu', 'create', 'left', 'spacer'];
        }

        return component;
    },

    getTopBar: function(config) {
        var tbar = [];
        var add = {
            menu: {
                text: '<i class="fa fa-cogs"></i> ',
                menu: [{
                    text: '<i class="fa fa-toggle-on green"></i> ' + _('gl_action_active'),
                    cls: 'gl-cogs',
                    handler: this.active,
                    scope: this
                }, {
                    text: '<i class="fa fa-toggle-off red"></i> ' + _('gl_action_inactive'),
                    cls: 'gl-cogs',
                    handler: this.inactive,
                    scope: this
                }, '-', /*{
                    text: '<i class="fa fa-plus"></i> ' + _('gl_action_create'),
                    cls: 'gl-cogs',
                    handler: this.create,
                    scope: this
                },*/ {
                    text: '<i class="fa fa-trash-o red"></i> ' + _('gl_action_remove'),
                    cls: 'gl-cogs',
                    handler: this.remove,
                    scope: this
                }]
            },
            create: {
                text: '<i class="fa fa-plus"></i>',
                handler: this.create,
                scope: this
            },
            left: '->',
            active: {
                xtype: 'gl-combo-active',
                width: 210,
                custm: true,
                clear: true,
                addall: true,
                value: '',
                listeners: {
                    select: {
                        fn: this._filterByCombo,
                        scope: this
                    },
                    afterrender: {
                        fn: this._filterByCombo,
                        scope: this
                    }
                }
            },
            search: {
                xtype: 'gl-field-search',
                width: 210,
                listeners: {
                    search: {
                        fn: function (field) {
                            this._doSearch(field);
                        },
                        scope: this
                    },
                    clear: {
                        fn: function (field) {
                            field.setValue('');
                            this._clearSearch();
                        },
                        scope: this
                    }
                }
            },
            spacer: {
                xtype: 'spacer',
                style: 'width:1px;'
            }
        };

        var cmp = this.getTopBarComponent(config);
        for (var i = 0; i < cmp.length; i++) {
            var item = cmp[i];
            if (add[item]) {
                tbar.push(add[item]);
            }
        }

        return tbar;
    },

    //var fields = ['id', 'identifier', 'class', 'phone', 'email', 'address', 'default', 'actions'];

    getColumns: function(config) {
        var columns = [this.exp, this.sm];
        var add = {
            id: {
                width: 10,
                sortable: false
            },
            class: {
                width: 15,
                sortable: false,
                renderer: function (value, metaData, record) {
                    switch (value) {
                        case 'glCity':
                            value = _('gl_city');
                            break;
                        case 'glRegion':
                            value = _('gl_region');
                            break;
                        case 'glCountry':
                            value = _('gl_country');
                            break;
                        default:
                            value = _('gl_default');
                    }
                    return value;
                }
            },
            identifier: {
                width: 25,
                sortable: false,
                renderer: function (value, metaData, record) {
                    switch (true) {
                        case record['json']['name'] != null:
                            value = record['json']['name'];
                            break;
                        case record['json']['name1'] != null:
                            value = record['json']['name1'];
                            break;
                        case record['json']['name2'] != null:
                            value = record['json']['name2'];
                            break;
                        default:
                            value = _('gl_default');
                    }
                    return gl.utils.renderReplace(record['json']['identifier'], value)
                }
            },
            phone: {
                width: 20,
                sortable: false,
                editor: {
                    xtype: 'textfield',
                    allowBlank: true
                }
            },
            email: {
                width: 20,
                sortable: false,
                editor: {
                    xtype: 'textfield',
                    allowBlank: true
                }
            },
            actions: {
                width: 20,
                sortable: false,
                renderer: gl.utils.renderActions,
                id: 'actions'
            }
        };

        if (!!config.compact) {

        }

        for (var field in add) {
            if (add[field]) {
                Ext.applyIf(add[field], {
                    header: _('gl_header_' + field),
                    tooltip: _('gl_tooltip_' + field),
                    dataIndex: field
                });
                columns.push(add[field]);
            }
        }

        return columns;
    },

    getListeners: function(config) {
        return {};
    },

    getMenu: function(grid, rowIndex) {
        var ids = this._getSelectedIds();
        var row = grid.getStore().getAt(rowIndex);
        var menu = gl.utils.getMenu(row.data['actions'], this, ids);
        this.addContextMenuItem(menu);
    },

    onClick: function(e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                } else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    setAction: function(method, field, value) {
        var ids = this._getSelectedIds();
        if (!ids.length && (field !== 'false')) {
            return false;
        }
        MODx.Ajax.request({
            url: gl.config.connector_url,
            params: {
                action: 'mgr/data/multiple',
                method: method,
                field_name: field,
                field_value: value,
                ids: Ext.util.JSON.encode(ids)
            },
            listeners: {
                success: {
                    fn: function() {
                        this.refresh();
                    },
                    scope: this
                },
                failure: {
                    fn: function(response) {
                        MODx.msg.alert(_('error'), response.message);
                    },
                    scope: this
                }
            }
        })
    },

    active: function(btn, e) {
        this.setAction('setproperty', 'active', 1);
    },

    inactive: function(btn, e) {
        this.setAction('setproperty', 'active', 0);
    },

    remove: function() {
        Ext.MessageBox.confirm(
            _('gl_action_remove'),
            _('gl_confirm_remove'),
            function(val) {
                if (val == 'yes') {
                    this.setAction('remove');
                }
            },
            this
        );
    },

    update: function(btn, e, row) {
        var record = typeof(row) != 'undefined' ? row.data : this.menu.record;
        MODx.Ajax.request({
            url: gl.config.connector_url,
            params: {
                action: 'mgr/data/get',
                id: record.id
            },
            listeners: {
                success: {
                    fn: function(r) {
                        var record = r.object;
                        var w = MODx.load({
                            xtype: 'gl-window-create-data',
                            title: _('gl_action_update'),
                            action: 'mgr/data/update',
                            record: record,
                            update: true,
                            listeners: {
                                success: {
                                    fn: this.refresh,
                                    scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(record);
                        w.show(e.target);
                    },
                    scope: this
                }
            }
        });
    },

    create: function(btn, e) {
        var record = {
            active: 1,
            default: 0,
            class: 'glCity'
        };

        w = MODx.load({
            xtype: 'gl-window-create-data',
            record: record,
			fileUpload: true,
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
        w.reset();
        w.setValues(record);
        w.show(e.target);
    },

    _filterByCombo: function (cb) {
        this.getStore().baseParams[cb.name] = cb.value;
        this.getBottomToolbar().changePage(1);
    },

    _doSearch: function(tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function() {
        this.getStore().baseParams.query = '';
        this.getBottomToolbar().changePage(1);
    },

    _getSelectedIds: function() {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    }

});
Ext.reg('gl-grid-data', gl.grid.Data);

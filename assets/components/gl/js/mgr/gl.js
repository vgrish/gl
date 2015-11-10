var gl = function (config) {
	config = config || {};
	gl.superclass.constructor.call(this, config);
};
Ext.extend(gl, Ext.Component, {
	page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('gl', gl);

gl = new gl();
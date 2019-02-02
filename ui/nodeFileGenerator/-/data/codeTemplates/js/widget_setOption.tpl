(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        _setOption: function (key, value) {
            this._super("_setOption", key, value);
        },

        options: {},

        __create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;


        }
    });
})(__nodeNs__, __nodeId__);

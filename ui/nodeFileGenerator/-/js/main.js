(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var widget = this;

            var $input = $("input", widget.element);

            $input.focus();
        }
    });
})(__nodeNs__, __nodeId__);

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var widget = this;


            var $widget = widget.element;

            var $ace = $widget.find(".ace_editor");

            if ($ace.length) {
                var id = $ace.attr("id");

                $('#' + id).height(height);

                var editor = ace.edit(id);

                editor.resize();
            }
        }
    });
})(__nodeNs__, __nodeId__);

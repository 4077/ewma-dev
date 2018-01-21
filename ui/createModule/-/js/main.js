// head {
var __nodeId__ = "ewma_dev_ui_createModule__main";
var __nodeNs__ = "ewma_dev_ui_createModule";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var widget = this;

            var $input = $("input", widget.element);

            $input.focus();

            $input.rebind("keyup." + __nodeId__ + " paste." + __nodeId__ + " change." + __nodeId__, function (e) {
                if (e.type === 'keyup' && e.keyCode === 13) {
                    request(widget.options.paths.create, {
                        path_tail: $input.val()
                    });
                }
            });
        }
    });
})(__nodeNs__, __nodeId__);

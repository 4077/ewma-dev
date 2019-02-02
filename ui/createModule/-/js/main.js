// head {
var __nodeId__ = "ewma_dev_ui_createModule__main";
var __nodeNs__ = "ewma_dev_ui_createModule";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var widget = this;

            var $pathTail = $("input.tail", widget.element);
            var $namespace = $("input.namespace", widget.element);

            $pathTail.focus();

            var events = "keyup." + __nodeId__ + " paste." + __nodeId__ + " change." + __nodeId__;

            $pathTail.rebind(events, function (e) {
                if (e.type === 'keyup' && e.keyCode === 13) {
                    submit();
                }
            });

            $namespace.rebind(events, function (e) {
                if (e.type === 'keyup' && e.keyCode === 13) {
                    submit();
                }
            });

            $namespace.rebind("keydown." + __nodeId__, function (e) {
                if (!$namespace.val() && e.keyCode === 9 && !e.ctrlKey) {
                    $namespace.val($namespace.attr("placeholder"));

                    request(widget.options.paths.updateNamespace, {
                        value: $namespace.val()
                    });

                    return false;
                }
            });

            var submit = function () {
                request(widget.options.paths.create, {
                    path_tail: $pathTail.val(),
                    namespace: $namespace.val()
                });
            }
        }
    });
})(__nodeNs__, __nodeId__);

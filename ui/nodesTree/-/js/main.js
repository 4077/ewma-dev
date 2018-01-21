// head {
var __nodeId__ = "ewma_dev_ui_nodesTree__main";
var __nodeNs__ = "ewma_dev_ui_nodesTree";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var widget = this;

            var $widget = widget.element;

            $(".indent.clickable", $widget).rebind("click", function (e) {
                request(widget.options.paths.toggleSubnodes, {
                    module_path: widget.options.modulePath,
                    node_path:   $(this).closest(".nodes").attr("node_path")
                });

                e.stopPropagation();
            });

            $(".node", $widget).rebind("contextmenu", function (e) {
                request(widget.options.paths.generatorDialog, {
                    module_path: widget.options.modulePath,
                    node_path:   $(this).closest(".nodes").attr("node_path")
                });

                e.stopPropagation();

                return false;
            });

            $(".name", $widget).rebind("click", function (e) {
                request(widget.options.paths.select, {
                    module_path: widget.options.modulePath,
                    node_path:   $(this).closest(".nodes").attr("node_path")
                });

                e.stopPropagation();
            });

            $(".select_type_button", $widget).rebind("click", function (e) {
                request(widget.options.paths.select, {
                    module_path: widget.options.modulePath,
                    node_path:   $(this).closest(".nodes").attr("node_path"),
                    type:        $(this).attr("type")
                });

                e.stopPropagation();
            });
        }
    });
})(__nodeNs__, __nodeId__);

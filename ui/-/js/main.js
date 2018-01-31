// head {
var __nodeId__ = "ewma_dev_ui__main";
var __nodeNs__ = "ewma_dev_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var widget = this;
            var $widget = this.element;
            var options = widget.options;

            var $modules = $("> .modules", $widget);
            var $nodes = $("> .nodes", $widget);

            $modules.scrollLeft(options.viewports.modules.scroll[0]).scrollTop(options.viewports.modules.scroll[1]);
            $nodes.scrollLeft(options.viewports.nodes.scroll[0]).scrollTop(options.viewports.nodes.scroll[1]);

            var scrollTimeout;

            $modules.rebind("scroll." + __nodeId__, function () {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }

                scrollTimeout = setTimeout(function () {
                    request(options.paths.updateViewport, {
                        viewport: 'modules',
                        scroll:   {
                            top:  $modules.scrollTop(),
                            left: $modules.scrollLeft()
                        }
                    }, null, true);
                }, 400);
            });

            $nodes.rebind("scroll." + __nodeId__, function () {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }

                scrollTimeout = setTimeout(function () {
                    request(options.paths.updateViewport, {
                        viewport: 'nodes',
                        scroll:   {
                            top:  $nodes.scrollTop(),
                            left: $nodes.scrollLeft()
                        }
                    }, null, true);
                }, 400);
            });
        }
    });
})(__nodeNs__, __nodeId__);

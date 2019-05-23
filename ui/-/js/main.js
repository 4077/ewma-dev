// head {
var __nodeId__ = "ewma_dev_ui__main";
var __nodeNs__ = "ewma_dev_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            var $modules = $("> .modules > .tree", $w);
            var $nodes = $("> .nodes", $w);

            $modules.scrollLeft(o.viewports.modules.scroll[0]).scrollTop(o.viewports.modules.scroll[1]);
            $nodes.scrollLeft(o.viewports.nodes.scroll[0]).scrollTop(o.viewports.nodes.scroll[1]);

            var scrollTimeout;

            $modules.rebind("scroll." + __nodeId__, function () {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function () {
                    request(o.paths.updateViewport, {
                        viewport: 'modules',
                        scroll:   {
                            top:  $modules.scrollTop(),
                            left: $modules.scrollLeft()
                        }
                    }, null, true);
                }, 400);
            });

            $nodes.rebind("scroll." + __nodeId__, function () {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function () {
                    request(o.paths.updateViewport, {
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

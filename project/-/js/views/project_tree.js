$.fn.dev_project_tree = function (data) {
    var context = $(this);

    dev_project_tree.update_height();

    $(window).resize(function () {
        dev_project_tree.update_height();
    });

    $(".indent.clickable", context).rebind("click", function (e) {
        request(data.paths.toggle_subnodes, {
            module_path: $(this).closest(".nodes").attr("module_path")
        });

        e.stopPropagation();
    });

    $(".name", context).rebind("click", function (e) {
        request(data.paths.set_module_view, {
            module_path: $(this).closest(".nodes").attr("module_path")
        });

        e.stopPropagation();
    });

    $(".action_button").rebind("click", function (e) {
        request(data.paths.input + ':' + $(this).attr("action"), {
            module_path: $(this).closest(".nodes").attr("module_path"),
            node_type:   $(this).attr("type")
        });

        e.stopPropagation();
    });

    var scrollTimeout;
    $(this).scrollTop(data.scrollTop).rebind("scroll", function () {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }

        scrollTimeout = setTimeout(function () {
            request(data.paths.setScrollTop, {
                value: context.scrollTop()
            });
        }, 200);
    });
};

var dev_project_tree = {

    update_height: function () {
        $("#dev_project_tree").height($(window).height());
    }
};

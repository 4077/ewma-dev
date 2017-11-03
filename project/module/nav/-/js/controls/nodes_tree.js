$.fn.dev_project_module_nav_nodes_tree = function (data)
{
    var context = $(this);

    $(".indent.clickable", context).rebind("click", function (e)
    {
        request(data.paths.toggle_subnodes, {
            module_path: data.module_path,
            node_path: $(this).closest(".nodes").attr("node_path")
        });

        e.stopPropagation();
    });

    $(".name", context).rebind("click", function (e)
    {
        request(data.paths.set_node_view, {
            module_path: data.module_path,
            node_path:  $(this).closest(".nodes").attr("node_path")
        });

        e.stopPropagation();
    });

    $(".node", context).rebind("contextmenu", function (e)
    {
        request(data.paths.generators_dialog, {
            module_path: data.module_path,
            node_path:  $(this).closest(".nodes").attr("node_path")
        });

        e.stopPropagation();
        return false;
    });

    $(".open_view_button", context).rebind("click", function (e)
    {
        request(data.paths.set_node_view, {
            module_path: data.module_path,
            node_path:  $(this).closest(".nodes").attr("node_path"),
            node_view:  $(this).attr("view")
        });

        e.stopPropagation();
    });
};

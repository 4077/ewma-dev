$.fn.dev_project_module_nav_models_tree = function (data)
{
    var context = $(this);

    $(".indent.clickable", context).rebind("click", function (e)
    {
        request(data.paths.toggle_subnodes, {
            module_path: data.module_path,
            model_path:  $(this).closest(".nodes").attr("model_path")
        });

        e.stopPropagation();
    });

    $(".name", context).rebind("click", function (e)
    {
        request(data.paths.set_model_view + ':model', {
            module_path: data.module_path,
            model_path:  $(this).closest(".nodes").attr("model_path")
        });

        e.stopPropagation();
    });
};

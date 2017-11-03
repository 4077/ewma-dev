var dev_project_module_editor = {

    bind: function (data)
    {
        var container = $("#" + data.container_id);

        var offset = container.offset();

        container.width($(window).width() - offset.left);
        container.height($(window).height() - offset.top);
    }
}
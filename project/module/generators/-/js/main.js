var dev_project_module_generators = {

    bind: function (data) {
        var context = $("#dev_project_module_generators");

        var updateTimeout = 0;
        $(".node_path_input", context).rebind("input", function () {
            var input = $(this);

            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(function () {
                request(data.paths.update_node_path, {
                    value: input.val()
                });
            }, 400);
        });
    }
};

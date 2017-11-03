var dev_project_module_editor = {

    bind: function (data)
          {
              var editor = this;

              //

              this._update_rect(data.container_id);

              $(window).rebind("resize.dev_project_module_editor", function ()
              {
                  editor._update_rect(data.container_id);
              });
          },

    _update_rect: function (container_id)
                  {
                      var container = $("#" + container_id);

                      if (container.length)
                      {
                          var offset = container.offset();

                          var ace_editor = ace.edit(container_id);

                          container.width($(window).width() - offset.left);
                          container.height($(window).height() - offset.top);

                          setTimeout(function ()
                                     {
                                         ace_editor.resize();
                                     }, 0);
                      }
                      else
                      {
                          $(window).unbind("resize.dev_project_module_editor");
                      }
                  }
};

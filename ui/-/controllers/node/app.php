<?php namespace ewma\dev\ui\controllers\node;

class App extends \Controller
{
    public function onSelectNode()
    {
        $s = &$this->s('~|');

        remap($s, $this->data, '
            selected_module_path    module_path,
            selected_node_path      node_path,
            selected_node_type      type
        ');

        $this->c('~:reload|');
    }

    public function onUpdate()
    {
        $s = &$this->s('~|');

        remap($s, $this->data, '
            selected_module_path    module_path,
            selected_node_path      node_path,
            selected_node_type      type
        ');

//        $this->c('~:reload|');
        $this->app->response->reload();
    }
}

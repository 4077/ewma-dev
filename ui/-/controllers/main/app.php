<?php namespace ewma\dev\ui\controllers\main;

class App extends \Controller
{
    public function onSelectModule()
    {
        $this->s('~:selected_module_path|', $this->data('module_path'), RR);

        $this->c('~:reload|');
    }

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

        $this->c('~:reload|');
    }
}

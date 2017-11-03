<?php namespace dev\project\module\nav\controllers\input;

class Nodes_tree extends \Controller
{
    public $allow = self::XHR;

    public function toggle_subnodes()
    {
        $sMain = &$this->s('~');

        toggle($sMain['expand']['trees_by_modules']['nodes'][$this->data['module_path']], $this->data['node_path']);

        $this->c('controls/nodes_tree:reload');
    }

    public function generators_dialog()
    {
        $this->c('@generators~:reset_node_path');

        $this->c('\std\ui\dialogs~:open:generators|dev', [
            'path' => '@generators~:view',
            'data' => [
                'module_path' => $this->data['module_path'],
                'node_path'   => $this->data['node_path']
            ]
        ]);
    }
}
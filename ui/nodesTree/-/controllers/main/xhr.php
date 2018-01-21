<?php namespace ewma\dev\ui\nodesTree\controllers\main;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function toggleSubnodes()
    {
        $s = &$this->s('~:expand_nodes|');

        toggle($s, $this->data('node_path'));

        $this->c('~:reload|');
    }

    public function select()
    {
        $this->c('~app:setType|', [], true);

        $this->c('~:reload|');
    }

    public function generatorDialog()
    {
        $this->c('\std\ui\dialogs~:open:nodeFileGenerator|ewma/dev', [
            'path'                => '\ewma\dev\ui\nodeFileGenerator~:view',
            'data'                => [
                'module_path' => $this->data['module_path'],
                'node_path'   => $this->data['node_path'],
                'callbacks'   => [
                    'generate' => $this->_abs('~app:onNodeFileCreate|')
                ]
            ],
            'pluginOptions/title' => 'Create node'
        ]);
    }
}

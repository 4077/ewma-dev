<?php namespace ewma\dev\ui\controllers;

class Main extends \Controller
{
    public function __create()
    {
        $this->smap('|', 'selected_module_path, selected_node_path, selected_node_type');

        $this->s(false, [
            'modules_width'  => 300,
            'modules_scroll' => [0, 0],
            'nodes_width'    => 300,
            'nodes_scroll'   => [0, 0]
        ]);
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $s = $this->s();

        $v->assign([
                       'MODULES_WIDTH' => $s['modules_width'],
                       'NODES_WIDTH'   => $s['nodes_width'],
                       'MODULES'       => $this->c('\ewma\dev\ui\modulesTree~:view|' . $this->_nodeInstance(), [
                           'dialogs'   => 'ewma/dev',
                           'callbacks' => [
                               'select' => $this->_p('>app:onSelectModule|')
                           ]
                       ], 'selected_module_path'),
                       'NODES'         => $this->c('\ewma\dev\ui\nodesTree~:view|' . $this->_nodeInstance(), [
                           'dialogs'     => 'ewma/dev',//
                           'module_path' => $this->data['selected_module_path'],
                           'node_path'   => $this->data['selected_node_path'],
                           'type'        => $this->data['selected_node_type'],
                           'callbacks'   => [
                               'select' => $this->_p('>app:onSelectNode|')
                           ]
                       ], 'selected_node_path, module_path selected_module_path'),
                       'NODE'          => $this->c('\ewma\dev\ui\node~:view|' . $this->_nodeInstance(), [
                           'module_path' => $this->data['selected_module_path'],
                           'node_path'   => $this->data['selected_node_path'],
                           'type'        => $this->data['selected_node_type'],
                           'callbacks'   => [
                               'typeSelect' => $this->_abs('>app:onSelectNode|'),
                               'update'     => $this->_abs('>app:onUpdate|')
                           ]
                       ])
                   ]);

        $this->css();

        $this->c('\std\ui\dialogs~:addContainer:ewma/dev');

        $this->widget(':|', [
            'paths'     => [
                'updateViewport' => $this->_p('>xhr:updateViewport')
            ],
            'viewports' => [
                'modules' => [
                    'scroll' => $s['modules_scroll']
                ],
                'nodes'   => [
                    'scroll' => $s['nodes_scroll']
                ]
            ]
        ]);

        return $v;
    }
}

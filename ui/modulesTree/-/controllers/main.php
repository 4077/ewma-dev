<?php namespace ewma\dev\ui\modulesTree\controllers;

class Main extends \Controller
{
    private $s;

    public function __create()
    {
        $this->s = &$this->s('|', [
            'expand_nodes'         => [],
            'selected_module_path' => null,
            'display'              => [
                'local'  => true,
                'vendor' => false
            ]
        ]);

        $this->smap('|', 'selected_module_path');
        $this->dmap('|', 'callbacks, dialogs');
    }

    public function performCallback($name, $data)
    {
        $callbacks = $this->data('callbacks');

        if (isset($callbacks[$name])) {
            $this->_call($callbacks[$name])->ra($data)->perform();
        }
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $this->css();

        $this->widget(':|', [
            'paths' => [
                'toggleSubnodes' => $this->_p('>xhr:toggleSubnodes|'),
                'selectModule'   => $this->_p('>xhr:selectModule|')
            ]
        ]);

        $v->assign('TREE', $this->treeView());

        return $v;
    }

    private $tree;

    private $level = 0;

    private function treeView($path = [])
    {
        $this->tree = \ewma\dev\Svc::getInstance()->getModulesTree();

        return $this->treeViewRecursion($path);
    }

    private function treeViewRecursion($modulePathArray)
    {
        $v = $this->v('>nodes');

        $modulePath = a2p($modulePathArray);
        $moduleName = $modulePathArray ? end($modulePathArray) : '/';

        $v->assign('nodes', [
            'MODULE_PATH' => $modulePath
        ]);

        $node = ap($this->tree, $modulePath);

        $settings = $node['-']['settings'];

        $display = $this->s['display'];

        if (empty($modulePathArray)) {
            $visible = true;
        } else {
            $visible = empty($settings['virtual']);

            if ($settings['location'] == 'local') {
                $visible &= $display['local'];
            }

            if ($settings['location'] == 'vendor') {
                $visible &= $display['vendor'];
            }
        }

        if ($visible) {
            $expand = $this->isExpand($modulePath);

            $nodeKeys = array_keys($node);

            $hasSubnodes = count(diff($nodeKeys, '-', true));

            $class = [];
            if ($this->data('selected_module_path') == $modulePath) {
                $class[] = 'current';
            }

            $class[] = $settings['type'];
            $class[] = $modulePath ? $settings['location'] : '';

            $v->assign('nodes/node', [
                'NAME'                   => $moduleName,
                'CLASS'                  => implode(' ', $class),
                'INDENT_WIDTH'           => ($this->level + 1) * 16 + 5,
                'INDENT_CLICKABLE_CLASS' => $hasSubnodes ? ' clickable' : '',
                'EXPAND_ICON_CLASS'      => $hasSubnodes ? ($expand ? 'rd_arrow' : 'r_arrow') : 'hidden',
                'MARGIN_LEFT'            => ($this->level) * 16 + 5,
                'NAME_MARGIN_LEFT'       => ($this->level + 1) * 16 + 5,
                'CREATE_BUTTON'          => $this->c('\std\ui button:view', [
                    'path'  => '>xhr:create|',
                    'data'  => [
                        'module_path' => $modulePath
                    ],
                    'class' => 'create button',
                    'icon'  => 'fa fa-plus'
                ]),
                'DELETE_BUTTON'          => $this->c('\std\ui button:view', [
                    'path'  => '>xhr:delete|',
                    'data'  => [
                        'module_path' => $modulePath
                    ],
                    'class' => 'delete button',
                    'icon'  => 'fa fa-trash'
                ])
            ]);

            if ($expand) {
                $v->assign('nodes/subnodes', [
                    'HIDDEN_CLASS' => ''
                ]);

                ksort($node);

                foreach ($node as $moduleName => $moduleData) {
                    if ($moduleName != '-') {
                        $modulePathArray[] = $moduleName;
                        $this->level++;

                        $v->assign('nodes/subnodes/subnode', [
                            'CONTENT' => $this->treeViewRecursion($modulePathArray)
                        ]);

                        $this->level--;
                        array_pop($modulePathArray);
                    }
                }
            }
        }

        return $v;
    }

    private function isExpand($modulePath)
    {
        return in_array($modulePath, $this->s['expand_nodes']);
    }
}

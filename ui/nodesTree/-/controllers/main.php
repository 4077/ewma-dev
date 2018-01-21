<?php namespace ewma\dev\ui\nodesTree\controllers;

class Main extends \Controller
{
    private $s;

    private $modulePath;

    private $nodePath;

    private $type;

    public function __create()
    {
        $commonS = $this->s(false, [
            'node_path' => '',
            'type'      => 'controller'
        ]);

        $this->s = &$this->s('|', [
            'expand_nodes'        => [],
            'node_path_by_module' => [],
            'type_by_module'      => []
        ]);

        $this->smap('|', 'module_path');
        $this->dmap('|', 'callbacks');

        $this->modulePath = $this->data['module_path'];

        if ($this->dataHas('node_path')) {
            $this->s['node_path_by_module'][$this->modulePath] = $this->data['node_path'];
        }

        if ($this->dataHas('type')) {
            $this->s['type_by_module'][$this->modulePath] = $this->data['type'];
        }

        if ($nodePath = $this->s['node_path_by_module'][$this->modulePath] ?? null) {
            $this->nodePath = $nodePath;
        } else {
            $this->nodePath = $commonS['node_path'];
        }

        if ($nodeType = $this->s['type_by_module'][$this->modulePath] ?? null) {
            $this->type = $nodeType;
        } else {
            $this->type = $commonS['type'];
        }
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

        $treeView = $this->treeView();

        if ($treeView) {
            $v->assign('CONTENT', $treeView);
        } else {
            $v->assign('CONTENT', $this->c('\std\ui button:view', [
                'path'    => '>xhr:generatorDialog',
                'data'    => [
                    'module_path' => $this->data('module_path'),
                    'node_path'   => 'main'
                ],
                'class'   => 'generators_dialog_button',
                'content' => '<div>generate</div>'
            ]));
        }

        $this->css(':\ewma\dev\ui nodeTypesColors');

        $this->widget(':|', [
            'paths'      => [
                'toggleSubnodes'  => $this->_p('>xhr:toggleSubnodes|'),
                'select'          => $this->_p('>xhr:select|'),
                'generatorDialog' => $this->_p('>xhr:generatorDialog|')
            ],
            'modulePath' => $this->data('module_path')
        ]);

        return $v;
    }

    private $types = ['controller', 'js', 'css', 'less', 'template', 'session', 'storage'];

    private $tree;

    private $level = 0;

    private function treeView($path = [])
    {
        $this->tree = \ewma\dev\Svc::getInstance()->getNodesTree($this->data('module_path'));

        if ($this->tree) {
            return $this->treeViewRecursion($path);
        }
    }

    private function treeViewRecursion($path)
    {
        $v = $this->v('>nodes');

        $nodePath = a2p($path);

        $v->assign('nodes', [
            'NODE_PATH' => $nodePath
        ]);

        $subnodes = ap($this->tree, $path);

        $nodeName = end($path);

        if ($nodeName != '.') {
            $isCurrentNode = $this->nodePath == $nodePath;
            $expand = !$path || $this->isExpand($nodePath);

            if ($path) {
                $nodeTypes = \ewma\dev\Svc::getInstance()->getNodeTypes($this->modulePath, $nodePath);

                diff($nodeTypes, ['session', 'storage']);

                $v->assign('nodes/node', [
                    'NAME'                   => $nodeName,
                    'CLASS'                  => ($isCurrentNode ? 'current' : '') . ($nodeTypes ? ' has_files ' . implode(' ', $nodeTypes) : ''),
                    'INDENT_WIDTH'           => $this->level * 16 + 5,
                    'INDENT_CLICKABLE_CLASS' => $this->hasNestedNodes($subnodes) ? ' clickable' : '',
                    'EXPAND_ICON_CLASS'      => $this->hasNestedNodes($subnodes) ? ($expand ? 'rd_arrow' : 'r_arrow') : 'hidden',
                    'MARGIN_LEFT'            => ($this->level - 1) * 16 + 5,
                    'NAME_MARGIN_LEFT'       => $this->level * 16 + 5
                ]);

                foreach ($this->types as $type) {
                    $selected_class = '';
                    if ($isCurrentNode && $this->type == $type) {
                        $selected_class = 'selected';
                    }

                    $content = '';
                    $hasData = in_array($type, $nodeTypes);

                    if ($type == 'session') {
                        $content = 's';
                        $hasData = !empty($subnodes['.']['session']['not_empty']);
                    }

                    if ($type == 'storage') {
                        $content = 'd';
                        $hasData = !empty($subnodes['.']['storage']['not_empty']);
                    }

                    $v->assign('nodes/node/type', [
                        'NAME'           => $type,
                        'CONTENT'        => $content,
                        'HAS_DATA_CLASS' => $hasData ? 'has_data' : '',
                        'SELECTED_CLASS' => $selected_class
                    ]);
                }
            }

            if ($subnodes && $expand) {
                ksort($subnodes);

                $v->assign('nodes/subnodes', [
                    'HIDDEN_CLASS' => ''
                ]);

                foreach ($subnodes as $nodeName => $node_data) {
                    $path[] = $nodeName;
                    $this->level++;

                    $v->assign('nodes/subnodes/subnode', [
                        'CONTENT' => $this->treeViewRecursion($path)
                    ]);

                    $this->level--;
                    array_pop($path);
                }
            }
        }

        return $v;
    }

    private function hasNestedNodes($subnodes)
    {
        return implode(',', array_keys($subnodes)) != '.';
    }

    private function isExpand($nodePath)
    {
        return in_array($nodePath, $this->s['expand_nodes']);
    }
}

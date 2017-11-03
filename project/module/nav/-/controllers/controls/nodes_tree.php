<?php namespace dev\project\module\nav\controllers\controls;

class Nodes_tree extends \Controller
{
    private $views = ['controller', 'js', 'css', 'less', 'template', 'session', 'storage'];

    //

    private $sMain;

    private $modulePath;
    private $module_namespace;
    private $nodePath;
    private $node_view;

    public function __create()
    {
        $this->sMain = $this->s('~');

        $sProjectMain = $this->s('^');
        $sNodeMain = $this->s('@node~');

        $this->modulePath = isset($sProjectMain['current_module_path']) ? $sProjectMain['current_module_path'] : '';
//        $this->module_namespace = $this->get_module($sProjectMain['current_module_path'])->namespace;

        if (isset($sNodeMain['current_path_by_module'][$this->modulePath])) {
            $this->nodePath = $sNodeMain['current_path_by_module'][$this->modulePath];
        } else {
            $this->nodePath = $sNodeMain['current_path_through_project'];
        }

        $this->node_view = $sNodeMain['current_view'];
    }

    //

    private function get_module($path)
    {
        return $this->app->modules->getByPath($path);
    }

    public function reload()
    {
        $this->jquery('#dev_project_module_nav_nodes_tree')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v();

        $this->css();

        $this->jquery(':#dev_project_module_nav_nodes_tree')
            ->dev_project_module_nav_nodes_tree([
                                                    'paths'       => [
                                                        'toggle_subnodes'   => $this->_p('input/nodes_tree:toggle_subnodes'),
                                                        'set_node_view'     => $this->_p('^module input/set_view:node'),
                                                        'generators_dialog' => $this->_p('input/nodes_tree:generators_dialog')
                                                    ],
                                                    'module_path' => $this->modulePath
                                                ]);

        $tree_view = $this->tree_view();

        if ($tree_view) {
            $v->assign('TREE', $tree_view);
        } else {
            $v->assign('GENERATORS_DIALOG_BUTTON', $this->c('\std\ui button:view', [
                'path'    => 'input/nodes_tree:generators_dialog',
                'data'    => [
                    'module_path' => $this->modulePath,
                    'node_path'   => 'main'
                ],
                'class'   => 'generators_dialog_button',
                'content' => 'generate'
            ]));
        }

        return $v;
    }

    //

    private function has_nested_nodes($subnodes)
    {
        return implode(',', array_keys($subnodes)) != '.';
    }

    private function is_expand($node_path)
    {
        if (isset($this->sMain['expand']['trees_by_modules']['nodes'][$this->modulePath])) {
            if (in_array($node_path, $this->sMain['expand']['trees_by_modules']['nodes'][$this->modulePath])) {
                return true;
            }
        }
    }

    private $tree, $level = 0;

    private function tree_view($path = [])
    {
        $this->tree = $this->c('^services/nodes_tree:get_nodes_tree:' . $this->modulePath);

        if ($this->tree) {
            return $this->tree_view_recursion($path);
        }
    }

    private function tree_view_recursion($path)
    {
        $v = $this->v('@nodes_tree_nodes');

        $node_path = a2p($path);

        $v->assign('nodes', [
            'NODE_PATH' => $node_path
        ]);

        $subnodes = ap($this->tree, $path);

        $node_name = end($path);

        if ($node_name != ".") {
            $is_current_node = $this->nodePath == $node_path;
            $expand = !$path || $this->is_expand($node_path);

            if ($path) {
                $node_types = $this->c('^services/nodes_tree')->get_node_types($this->modulePath, $node_path);

                diff($node_types, ['session', 'storage']);

                $v->assign('nodes/node', [
                    'NAME'                   => $node_name,
                    'CLASS'                  => ($is_current_node ? 'current' : '') . ($node_types ? ' has_files ' . implode(' ', $node_types) : ''),
                    'INDENT_WIDTH'           => $this->level * 16 + 5,
                    'INDENT_CLICKABLE_CLASS' => $this->has_nested_nodes($subnodes) ? ' clickable' : '',
                    'EXPAND_ICON_CLASS'      => $this->has_nested_nodes($subnodes) ? ($expand ? 'rd_arrow' : 'r_arrow') : 'hidden',
                    'MARGIN_LEFT'            => ($this->level - 1) * 16 + 5,
                    'NAME_MARGIN_LEFT'       => $this->level * 16 + 5
                ]);

                foreach ($this->views as $view) {
                    $selected_class = '';
                    if ($is_current_node && $this->node_view == $view) {
                        $selected_class = 'selected';
                    }

                    $content = '';
                    $has_data = in_array($view, $node_types);

                    if ($view == 'session') {
                        $content = 's';
                        $has_data = !empty($subnodes['.']['session']['not_empty']);
                    }

                    if ($view == 'storage') {
                        $content = 'd';
                        $has_data = !empty($subnodes['.']['storage']['not_empty']);
                    }

                    $v->assign('nodes/node/view', [
                        'NAME'           => $view,
                        'CONTENT'        => $content,
                        'HAS_DATA_CLASS' => $has_data ? 'has_data' : '',
                        'SELECTED_CLASS' => $selected_class
                    ]);
                }
            }

            if ($subnodes && $expand) {
                ksort($subnodes);

                $v->assign('nodes/subnodes', [
                    'HIDDEN_CLASS' => ''
                ]);

                foreach ($subnodes as $node_name => $node_data) {
                    $path[] = $node_name;
                    $this->level++;

                    $v->assign('nodes/subnodes/subnode', [
                        'CONTENT' => $this->tree_view_recursion($path)
                    ]);

                    $this->level--;
                    array_pop($path);
                }
            }
        }

        return $v;
    }
}

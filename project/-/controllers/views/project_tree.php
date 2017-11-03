<?php namespace dev\project\controllers\views;

class Project_tree extends \Controller
{
    private $sMain;

    public function __create()
    {
        $this->sMain = $this->s('~');
    }

    //

    public function reload()
    {
        $j = $this->jquery('#dev_project_tree');
        $j->replace($this->render());
    }

    public function render()
    {
        $v = $this->v();
        $s = $this->s(false, [
            'scrollTop' => 0
        ]);

        $this->css();

        $this->jquery(':#dev_project_tree')
            ->dev_project_tree([
                                   'scrollTop' => $s['scrollTop'],
                                   'paths'        => [
                                       'toggle_subnodes' => $this->_p('input/project_tree:toggle_subnodes'),
                                       'set_module_view' => $this->_p('input/set_view:module'),
                                       'input'           => $this->_p('input/project_tree'),
                                       'setScrollTop'    => $this->_p('input/project_tree:setScrollTop'),
                                   ]
                               ]);

        $this->c('\plugins\perfectScrollbar~:bind', [
            'selector' => '#dev_project_tree',
            'options'  => []
        ]);

        $v->assign('TREE', $this->tree_view());

        return $v;
    }

    //

    private $tree, $level = 0;

    private function tree_view($path = [])
    {
        $this->tree = $this->c('services/project_tree:getProjectTree');

        return $this->tree_view_recursion($path);
    }

    private function is_expand($module_path)
    {
        return in_array($module_path, $this->sMain['expand_modules']);
    }

    private function tree_view_recursion($modulePathArray)
    {
        $v = $this->v('@project_tree_nodes');

        $modulePath = a2p($modulePathArray);
        $moduleName = $modulePathArray ? end($modulePathArray) : '/';

        $v->assign('nodes', [
            'MODULE_PATH' => $modulePath
        ]);

        $node = ap($this->tree, $modulePath);

        $expand = $this->is_expand($modulePath);

        $nodeKeys = array_keys($node);

        $hasSubnodes = count(diff($nodeKeys, '-', true));

        $class = '';
        if (isset($this->sMain['current_module_path']) && $this->sMain['current_module_path'] == $modulePath) {
            $class .= ' current';
        }

        $class .= ' ' . (isset($node['-']['settings']['type']) ? $node['-']['settings']['type'] : 'master');

        $v->assign('nodes/node', [
            'NAME'                   => $moduleName,
            'CLASS'                  => $class,
            'INDENT_WIDTH'           => ($this->level + 1) * 16 + 5,
            'INDENT_CLICKABLE_CLASS' => $hasSubnodes ? ' clickable' : '',
            'EXPAND_ICON_CLASS'      => $hasSubnodes ? ($expand ? 'rd_arrow' : 'r_arrow') : 'hidden',
            'MARGIN_LEFT'            => ($this->level) * 16 + 5,
            'NAME_MARGIN_LEFT'       => ($this->level + 1) * 16 + 5
        ]);

        if (true) {
            $v->assign('nodes/node/update_cache', [
                'CLASS' => 'update_cache'
            ]);
        }

        if ($expand) {
            $v->assign('nodes/subnodes', [
                'HIDDEN_CLASS' => ''
            ]);

            foreach ($node as $moduleName => $module_data) {
                if ($moduleName != '-') {
                    $modulePathArray[] = $moduleName;
                    $this->level++;

                    $v->assign('nodes/subnodes/subnode', [
                        'CONTENT' => $this->tree_view_recursion($modulePathArray)
                    ]);

                    $this->level--;
                    array_pop($modulePathArray);
                }
            }
        }

        return $v;
    }
}
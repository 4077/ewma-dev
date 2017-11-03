<?php namespace dev\project\module\nav\controllers\controls;

class Models_tree extends \Controller
{
    private $sMain;

    private $module_path;
    private $model_path;

    public function __create()
    {
        $this->sMain = $this->s('~');

        $sProjectMain = $this->s('^');
        $sModelMain = $this->s('@model~');

        $this->module_path = $sProjectMain['current_module_path'];

        if ( isset($sModelMain['current_path_by_module'][$this->module_path]) )
            $this->model_path = $sModelMain['current_path_by_module'][$this->module_path];
    }

    //

    public function reload()
    {
        $this->jquery('#dev_project_module_nav_models_tree')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v();

        $this->css();
        $this->jquery(':#dev_project_module_nav_models_tree')
                ->dev_project_module_nav_models_tree(array(
                                                             'paths'       => array(
                                                                     'toggle_subnodes' => $this->_p('input/models_tree:toggle_subnodes'),
                                                                     'set_model_view'  => $this->_p('^module input/set_view:model')
                                                             ),
                                                             'module_path' => $this->module_path
                                                     ));

        $v->assign('TREE', $this->tree_view());

        return $v;
    }

    //

    private $tree, $level = 0;

    private function tree_view($path = array())
    {
        $this->tree = $this->c('^services/models_tree:get_models_tree:' . $this->module_path);

        return $this->tree_view_recursion($path);
    }

    private function is_expand($model_path)
    {
        if ( isset($this->sMain['expand']['trees_by_modules']['models'][$this->module_path]) )
            if ( in_array($model_path, $this->sMain['expand']['trees_by_modules']['models'][$this->module_path]) )
                return true;
    }

    private function tree_view_recursion($path)
    {
        $v = $this->v('@models_tree_nodes');

        $model_path = a2p($path);
        $subnodes = ap($this->tree, $path);

        if ( $subnodes )
        {
            foreach ( $subnodes as $index => $data )
            {
                if ( $index != '.' ) // dirs
                {
                    $expand = $this->is_expand($model_path ? $model_path . '/' . $index : $index);

                    $v->assign('nodes',
                               array(
                                       'MODEL_PATH' => $model_path ? $model_path . '/' . $index : $index
                               ));

                    $v->assign('nodes/node',
                               array(
                                       'NAME'                   => $index,
                                       'CLASS'                  => '', // . ($model_types ? ' has_files ' . implode(' ', $model_types) : ''),
                                       'INDENT_WIDTH'           => ($this->level + 1) * 16 + 5,
                                       'INDENT_CLICKABLE_CLASS' => $subnodes ? ' clickable' : '',
                                       'EXPAND_ICON_CLASS'      => $subnodes ? ($expand ? 'rd_arrow' : 'r_arrow') : 'hidden',
                                       'MARGIN_LEFT'            => ($this->level) * 16 + 5,
                                       'NAME_MARGIN_LEFT'       => ($this->level + 1) * 16 + 5
                               ));

                    $v->assign('nodes/subnodes',
                               array(
                                       'HIDDEN_CLASS' => ''
                               ));

                    $path[] = $index;
                    $this->level++;

                    if ( $expand )
                    {
                        $v->assign('nodes/subnodes/subnode',
                                   array(
                                           'CONTENT' => $this->tree_view_recursion($path)
                                   ));
                    }

                    $this->level--;
                    array_pop($path);
                }
            }

            if ( isset($subnodes['.']) ) // files
            {
                foreach ( $subnodes['.'] as $model_file )
                {
                    $path[] = $model_file;
                    $model_path = a2p($path);

                    $is_current_model = $this->model_path == $model_path;

                    $v->assign('nodes',
                               array(
                                       'MODEL_PATH' => $model_path
                               ));

                    $v->assign('nodes/node',
                               array(
                                       'NAME'                   => $model_file,
                                       'CLASS'                  => 'file' . ($is_current_model ? ' current' : ''),
                                       'INDENT_WIDTH'           => ($this->level + 1) * 16 + 5,
                                       'INDENT_CLICKABLE_CLASS' => '',
                                       'MARGIN_LEFT'            => ($this->level) * 16 + 5,
                                       'NAME_MARGIN_LEFT'       => ($this->level + 1) * 16 + 5
                               ));

                    array_pop($path);
                }
            }
        }

        return $v;
    }
}
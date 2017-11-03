<?php namespace dev\project\module\nav\controllers\controls;

class Data_list extends \Controller
{
    private $sMain;

    private $module_path;
    private $node_path;
    private $node_type;

    public function __create()
    {
//        $this->sMain = $this->s('~');
//
//        $sProjectMain = $this->s('^');
//        $sNodeMain = $this->s('@node~');
//
//        $this->module_path = $sProjectMain['current_module_path'];
    }

    //

    public function reload()
    {
        $this->jquery('#dev_project_module_nav_data_list')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v();

        $this->css();

        $this->jquery(':#dev_project_module_nav_data_list')
                ->dev_project_module_nav_data_list(array(
                                                          'paths'       => array(
                                                              'toggle_subnodes' => $this->_p('input/nodes_tree:toggle_subnodes'),
                                                              'set_node_view'  => $this->_p('^module input/set_view:node')
                                                          ),

                                                          'module_path' => $this->module_path
                                                     ));



        return $v;
    }
}
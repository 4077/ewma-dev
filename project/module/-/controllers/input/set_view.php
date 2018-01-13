<?php namespace dev\project\module\controllers\input;

class Set_view extends \Controller
{
    public $allow = self::XHR | self::APP;

    private $modulePath;
    private $changeModule = false;

    public function __create()
    {
        $sProjectMain = $this->s('^');

        $modulePath = $sProjectMain['current_module_path'];

        if ($this->dataHas('module_path')) {
            if ($this->data['module_path'] != $modulePath) {
                $this->changeModule;
            }

            $modulePath = $this->data['module_path'];
        }

        $this->modulePath = $modulePath;
    }

    //

    private function setCurrentSection($section)
    {
        $sMain = &$this->s('~');
        $sMain['current_section'] = $section;
    }

    //

    public function node()
    {
        if ($this->dataHas('node_path')) {
            $this->setCurrentSection('node');

            $sNodeMain = &$this->s('node~');
            $sNodeMain['current_path_by_module'][$this->modulePath] = $this->data['node_path'];
            $sNodeMain['current_path_through_project'] = $this->data['node_path'];

            if ($this->dataHas('node_view')) {
                $sNodeMain['current_view'] = $this->data['node_view'];
            }

            $this->c('nav controls/nodes_tree:reload');
            $this->c('main:content_reload');
        }
    }

    public function model()
    {
        if ($this->dataHas('model_path')) {
            $this->setCurrentSection('model');

            $sModelMain = &$this->s('model~');
            $sModelMain['current_path_by_module'][$this->modulePath] = $this->data['model_path'];

            $this->c('nav controls/models_tree:reload');
            $this->c('main:content_reload');
        }
    }
}
<?php namespace dev\project\controllers\services;

class Models_tree extends \Controller
{
    public $singleton = true;

    public function get_models_tree($modulePath = false)
    {
        $projectTree = $this->c('@project_tree:getProjectTree');
        $module = ap($projectTree, $modulePath);

        return $module['-']['models'];
    }
}

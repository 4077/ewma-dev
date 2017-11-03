<?php namespace dev\project\controllers;

class Session extends \Controller
{
    public $singleton = true;

    public function init($reset = false)
    {
        $this->s('~', [
            'content_view'        => false, // module|...
            'current_module_path' => false,
            'expand_modules'      => []
        ], $reset);

        $this->s('module~', [
            'sections'        => ['settings', 'config', 'node', 'model', 'php', 'install', 'protected', 'public'],
            'current_section' => 'node'
        ], $reset);

        $this->s('module/nav~', [

            'expand' => [

                'sections'         => [
                    'nodes_tree'     => true,
                    'models_tree'    => false,
                    'php_tree'       => false,
                    'install_tree'   => false,
                    'protected_tree' => false,
                    'public_tree'    => false
                ],
                'trees_by_modules' => [
                    'nodes'     => [],
                    'models'    => [],
                    'php'       => [],
                    'install'   => [],
                    'protected' => [],
                    'public'    => []
                ]
            ]
        ], $reset);

        $this->s('module/node~', [
            'current_path_through_project' => false,
            'current_path_by_module'       => [],
            'current_view'                 => 'controller' // controller|js|css|less|template
        ], $reset);

        $this->s('module/model~', [
            'current_path_by_module' => [],
            //                      'current_view'           => 'controller' // controller|js|css|less|template
        ], $reset);
    }

    //

    public function gc($existing_modules_list)
    {
        $s = &$this->s('~');

        if (!empty($s['expand_modules'])) {
            $s['expand_modules'] = array_intersect($s['expand_modules'], $existing_modules_list);
        }

        // Nav
        $sModuleNav = &$this->s('module/nav~');
        if (!empty($sModuleNav['expand'])) {
            foreach (array_keys($sModuleNav['expand']['trees_by_modules']) as $tree_name) {
                $this->removeDataForNonExistentModules($sModuleNav['expand']['trees_by_modules'][$tree_name], $existing_modules_list);
            }
        }

        // Node
        $sModuleNode = &$this->s('module/node~');
        if (!empty($sModuleNode['current_path_by_module'])) {
            $this->removeDataForNonExistentModules($sModuleNode['current_path_by_module'], $existing_modules_list);
        }

        // Model
        $sModuleModel = &$this->s('module/model~');
        if (!empty($sModuleModel['current_path_by_module'])) {
            $this->removeDataForNonExistentModules($sModuleModel['current_path_by_module'], $existing_modules_list);
        }
    }

    private function removeDataForNonExistentModules(& $input, $existing_modules_list)
    {
        foreach ($input as $module_path => $_) {
            if (!in_array($module_path, $existing_modules_list)) {
                unset($input[$module_path]);
            }
        }
    }
}

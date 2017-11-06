<?php namespace dev\project\controllers\services;

use ewma\models\Session;
use ewma\models\Storage;

class Project_tree extends \Controller
{
    public $singleton = true;

    private $projectTree;

    public function getProjectTree()
    {
        if (!$this->projectTree) {
            $cacheFilePath = $this->_protected('cache', 'modules.json');

            if (!file_exists($cacheFilePath)) {
                $this->updateCache();
            }

            $this->projectTree = jread($cacheFilePath);
        }

        return $this->projectTree;
    }

    //


    private $modulesPathsFoundOnUpdateCache = [];

    public function updateCache($modulePath = '')
    {
        $cacheFilePath = $this->_protected('cache', 'modules.json');

        $modulesCache = $this->app->cache->read('modules');

        foreach ($modulesCache as $moduleCacheData) {
            $module = \ewma\Modules\Module::create($moduleCacheData);

            $this->modulesById[$module->id] = $module;
            $this->modulesIdsByParentIds[$module->parentId][] = $module->id;
        }

        $tree = $this->updateCacheRecursion();

//        $module = $this->app->modules->getByNamespace($modulePath);

//        $this->updateCacheRecursion(p2a($module->path));

//        if ($modulePath) {
//            $this->projectTree = jread($cacheFilePath);
//
//            $nodePath = $modulePath;
//
//            ap($this->projectTree, $nodePath, $tree);
//
//            jwrite($cacheFilePath, $this->projectTree);
//        } else {
        jwrite($cacheFilePath, $tree);

        $this->c('session')->gc($this->modulesPathsFoundOnUpdateCache);
//        }
    }

//    private $module

    private function updateCacheRecursion($id = 0)
    {
//        $modulePath = a2p($modulePathArray);
        $r = 0;
        if (isset($this->modulesById[$id])) {
            /* @var $module \ewma\Modules\Module */
            $module = $this->modulesById[$id];

            $this->modulesPathsFoundOnUpdateCache[] = $module->path;

            $node = [];

//        $modulesDir = $module->location == 'local'
//            ? 'modules'
//            : 'modules-vendor';

            $moduleDir = $module->getDir();// abs_path($modulePath ? $modulesDir : '', $modulePath);

            $node['-']['settings'] = ['type' => 'master'];

//        if ($modulePath) {
            ra($node['-']['settings'], $module->toCacheFormat());// $this->getModuleSettings($moduleDir));
//        }

            $node['-']['nodes'] = $this->getModuleNodesTree($moduleDir);
            $node['-']['models'] = $this->getModuleModelsTree($moduleDir);
        }

        if (isset($this->modulesIdsByParentIds[$id])) {
            $nestedModulesIds = $this->modulesIdsByParentIds[$id];

            foreach ($nestedModulesIds as $nestedModuleId) {
                $nestedModule = $this->modulesById[$nestedModuleId];

//                $node[path_slice($nestedModule->path, -1)] = $this->updateCacheRecursion($nestedModuleId);
                $node[path_slice($nestedModule->path, -1)] = $this->updateCacheRecursion($nestedModuleId);
            }
        }

        return $node;

        /*
         *
        $nestedModulesDir = $modulePath ? $moduleDir : abs_path('modules');

        $names = [];

        foreach (new \DirectoryIterator($nestedModulesDir) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if ($fileInfo->isDir()) {
                $dirName = $fileInfo->getFilename();
                if ($dirName != '-') {
                    $names[] = $dirName;
                }
            }
        }

        sort($names);

        foreach ($names as $dirName) {
            $modulePathArray[] = $dirName;

            $node[$dirName] = $this->updateCacheRecursion($modulePathArray);

            array_pop($modulePathArray);
        }

        */
    }

//    private $modulesPathsFoundOnUpdateCache = [];
//
//    public function updateCache($modulePath = '')
//    {
//        $cacheFilePath = $this->_protected('cache', 'modules.json');
//
//        $module = $this->app->modules->getByNamespace($modulePath);
//
//        $tree = $this->updateCacheRecursion(p2a($module->path));
//
//        if ($modulePath) {
//            $this->projectTree = jread($cacheFilePath);
//
//            $nodePath = $modulePath;
//
//            ap($this->projectTree, $nodePath, $tree);
//
//            jwrite($cacheFilePath, $this->projectTree);
//        } else {
//            jwrite($cacheFilePath, $tree);
//
//            $this->c('session')->gc($this->modulesPathsFoundOnUpdateCache);
//        }
//    }
//
//    private function updateCacheRecursion($modulePathArray)
//    {
//        $modulePath = a2p($modulePathArray);
//
//        $module = $this->app->modules->getByPath($modulePath);
//
//        $this->modulesPathsFoundOnUpdateCache[] = $module->path;
//
//        $node = [];
//
////        $modulesDir = $module->location == 'local'
////            ? 'modules'
////            : 'modules-vendor';
//
//        $moduleDir = $module->getDir();// abs_path($modulePath ? $modulesDir : '', $modulePath);
//
//        $node['-']['settings'] = ['type' => 'master'];
//
////        if ($modulePath) {
//            ra($node['-']['settings'], $this->getModuleSettings($moduleDir));
////        }
//
//        $node['-']['nodes'] = $this->getModuleNodesTree($moduleDir);
//        $node['-']['models'] = $this->getModuleModelsTree($moduleDir);
//
//        $nestedModulesDir = $modulePath ? $moduleDir : abs_path('modules');
//
//        $names = [];
//
//        foreach (new \DirectoryIterator($nestedModulesDir) as $fileInfo) {
//            if ($fileInfo->isDot()) {
//                continue;
//            }
//
//            if ($fileInfo->isDir()) {
//                $dirName = $fileInfo->getFilename();
//                if ($dirName != '-') {
//                    $names[] = $dirName;
//                }
//            }
//        }
//
//        sort($names);
//
//        foreach ($names as $dirName) {
//            $modulePathArray[] = $dirName;
//
//            $node[$dirName] = $this->updateCacheRecursion($modulePathArray);
//
//            array_pop($modulePathArray);
//        }
//
//        return $node;
//    }

//    private function updateCacheRecursion($modulePathArray)
//    {
//        $modulePath = a2p($modulePathArray);
//
//        $this->modulesPathsFoundOnUpdateCache[] = $modulePath;
//
//        $node = [];
//
//        if ($module = $this->app->modules->getByPath($modulePath)) {
//            $modulesDir = $module->location == 'local'
//                ? 'modules'
//                : 'modules-vendor';
//
//            $moduleDir = abs_path($modulePath ? $modulesDir : '', $modulePath);
//
//            $node['-']['settings'] = ['type' => 'master'];
//
//            if ($modulePathArray) {
//                ra($node['-']['settings'], $this->getModuleSettings($moduleDir));
//            }
//
//            $node['-']['nodes'] = $this->getModuleNodesTree($moduleDir);
//            $node['-']['models'] = $this->getModuleModelsTree($moduleDir);
//
//            $nestedModulesDir = $modulePath ? $moduleDir : abs_path('modules');
//
//            $names = [];
//
//            foreach (new \DirectoryIterator($nestedModulesDir) as $fileInfo) {
//                if ($fileInfo->isDot()) {
//                    continue;
//                }
//
//                if ($fileInfo->isDir()) {
//                    $fileName = $fileInfo->getFilename();
//                    if ($fileName != '-') {
//                        $names[] = $fileName;
//                    }
//                }
//            }
//
//            sort($names);
//
//            foreach ($names as $fileName) {
//                $modulePathArray[] = $fileName;
//
//                $node[$fileName] = $this->updateCacheRecursion($modulePathArray);
//
//                array_pop($modulePathArray);
//            }
//        }
//
//        return $node;
//    }

    // settings

    public function getModuleSettings($moduleDirAbsPath)
    {
        return require_once $moduleDirAbsPath . '/settings.php';
    }

    // nodes tree

    private $nodesTreeOutput = [];

    private function getModuleNodesTree($moduleDir)
    {
        $this->nodesTreeOutput = [];

        // files
        foreach (l2a('controllers, js, css, less, templates') as $type) {
            $ext = $type;

            if ($type == 'controllers') {
                $ext = 'php';
            }

            if ($type == 'templates') {
                $ext = 'tpl';
            }

            $this->getModuleNodesTreeTypeRecursion('/' . path($moduleDir, '-', $type), $ext, []);
        }

        // session, storage
        $moduleNamespace = $this->getModuleNamespace($moduleDir);

        if ($moduleNamespace) {
            $sessions = Session::where('module_namespace', $moduleNamespace)->get();
            foreach ($sessions as $session) {
                aa($this->nodesTreeOutput, [$session['node_path'] . '/./session' => ['not_empty' => !empty($session['data'])]]);
            }

            $storages = Storage::where('module_namespace', $moduleNamespace)->get();
            foreach ($storages as $storage) {
                aa($this->nodesTreeOutput, [$storage['node_path'] . '/./storage' => ['not_empty' => !empty($storage['data'])]]);
            }
        }

        return $this->nodesTreeOutput;
    }

    private function getModuleNodesTreeTypeRecursion($nodeTypeDir, $ext, $nodePathArray)
    {
        $nodePath = a2p($nodePathArray);

        $nodeDir = abs_path($nodeTypeDir, $nodePath);

        if (is_dir($nodeDir)) {
            foreach (new \DirectoryIterator($nodeDir) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }

                if ($fileInfo->isDir()) {
                    $nodePathArray[] = $fileInfo->getFilename();
                    aa($this->nodesTreeOutput, [a2p($nodePathArray) => []]);
                    $this->getModuleNodesTreeTypeRecursion($nodeTypeDir, $ext, $nodePathArray);
                    array_pop($nodePathArray);
                }

                if ($fileInfo->isFile()) {
                    if ($fileInfo->getExtension() == $ext) {
                        $nodePathArray[] = $fileInfo->getBasename('.' . $ext);
                        aa($this->nodesTreeOutput, [path(a2p($nodePathArray), '.', $ext) => []]);
                        array_pop($nodePathArray);
                    }
                }
            }
        }
    }

    //

    public function getModuleNamespace($module_abs_path)
    {
        if (file_exists($module_abs_path . '/settings.php')) {
            $module_config = require $module_abs_path . '/settings.php';

            return $module_config['namespace'] ?? '';
        }
    }

    // models tree

    private function getModuleModelsTree($module_abs_path)
    {
        return $this->get_module_models_tree_recursion($module_abs_path . '/-/models');
    }

    private function get_module_models_tree_recursion($models_dir_path, $node_path = [])
    {
        $node_path_str = $node_path ? '/' . implode('/', $node_path) : '';

        if (is_dir($models_dir_path . $node_path_str)) {
            $nodes = scandir($models_dir_path . $node_path_str);

            $output = [];

            foreach ($nodes as $node) {
                if ($node != '.' && $node != '..') {
                    if (is_dir($models_dir_path . $node_path_str . '/' . $node)) {
                        $node_path[] = $node;
                        $output[$node] = $this->get_module_models_tree_recursion($models_dir_path, $node_path);
                        array_pop($node_path);
                    }

                    if (is_file($models_dir_path . $node_path_str . '/' . $node)) {
                        $path_info = pathinfo($models_dir_path . $node_path_str . '/' . $node);

                        $output['.'][] = $path_info['filename'];
                    }
                }
            }

            return $output;
        }
    }
}

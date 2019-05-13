<?php namespace ewma\dev;

class Svc extends \ewma\service\Service
{
    /**
     * @var self
     */
    public static $instance;

    /**
     * @return \ewma\dev\Svc
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new self;
            static::$instance->__register__();
        }

        return static::$instance;
    }

    protected $services = [];

    //
    //
    //

    private $modulesTree;

    public function getModulesTree()
    {
        if (!$this->modulesTree) {
            $cacheFilePath = appc('\ewma\dev~')->_protected('cache', 'modules.php');

            if (!file_exists($cacheFilePath)) {
                $this->updateCache();
            }

            $this->modulesTree = aread($cacheFilePath);
        }

        return $this->modulesTree;
    }

    private $modulesById;

    private $modulesIdsByParentIds;

    private $modulesPathsFoundOnUpdateCache = [];

    public function updateCache()
    {
        $cacheFilePath = appc('\ewma\dev~')->_protected('cache', 'modules.php');

        $modulesCache = app()->modules->getAll();

        foreach ($modulesCache as $module) {
            $this->modulesById[$module->id] = $module;
            $this->modulesIdsByParentIds[$module->parentId][] = $module->id;
        }

        $tree = $this->updateCacheRecursion();

        awrite($cacheFilePath, $tree);

        // todo $this->c('session')->gc($this->modulesPathsFoundOnUpdateCache);
    }

    private function updateCacheRecursion($id = 0)
    {
        $node = [];

        if (isset($this->modulesById[$id])) {
            /* @var $module \ewma\Modules\Module */
            $module = $this->modulesById[$id];

            $this->modulesPathsFoundOnUpdateCache[] = $module->path;

            $node['-']['settings'] = ['type' => 'master'];

            ra($node['-']['settings'], $module->toCacheFormat());

            $node['-']['nodes'] = $this->getModuleNodesTree($module);
            $node['-']['models'] = $this->getModuleModelsTree($module);
        }

        if (isset($this->modulesIdsByParentIds[$id])) {
            $nestedModulesIds = $this->modulesIdsByParentIds[$id];

            foreach ($nestedModulesIds as $nestedModuleId) {
                $nestedModule = $this->modulesById[$nestedModuleId];

                $moduleName = path_slice($nestedModule->path, -1);

                ap($node, $moduleName, $this->updateCacheRecursion($nestedModuleId));
            }
        }

        return $node;
    }

    // nodes tree

    private $nodesTreeOutput = [];

    private function getModuleNodesTree($module)
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

            $this->getModuleNodesTreeTypeRecursion('/' . path($module->dir, '-', $type), $ext, []);
        }

        // session, storage

        if ($module->namespace) {
            $sessions = \ewma\models\Session::where('module_namespace', $module->namespace)->get();
            foreach ($sessions as $session) {
                aa($this->nodesTreeOutput, [$session['node_path'] . '/./session' => ['not_empty' => !empty($session['data'])]]);
            }

            $storages = \ewma\models\Storage::where('module_namespace', $module->namespace)->get();
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

    public function getNodesTree($modulePath = false)
    {
        $modulesTree = $this->getModulesTree();

        return ap($modulesTree, path($modulePath, '-/nodes'));
    }

    public function getNodeTypes($modulePath, $nodePath = false)
    {
        $modulesTree = $this->getModulesTree();

        $node = ap($modulesTree, path($modulePath, '-/nodes', $nodePath));

        $types = [];

        if (isset($node['.'])) {
            $exts = array_keys($node['.']);

            foreach ($exts as $ext) {
                if ($ext == 'php') {
                    $type = 'controller';
                } elseif ($ext == 'tpl') {
                    $type = 'template';
                } else {
                    $type = $ext;
                }

                $types[] = $type;
            }
        }

        return $types;
    }

    public function getTypeExtension($type)
    {
        $extensions = [
            'controller' => 'php',
            'js'         => 'js',
            'css'        => 'css',
            'less'       => 'less',
            'template'   => 'tpl'
        ];

        return $extensions[$type] ?? '';
    }

    public function getTypeDir($type)
    {
        $dirs = [
            'controller' => 'controllers',
            'js'         => 'js',
            'css'        => 'css',
            'less'       => 'less',
            'template'   => 'templates'
        ];

        return $dirs[$type] ?? '';
    }

    public function getCachePath($modulePath, $nodePath, $type)
    {
        $nodeFileMd5 = $this->getNodeFileMd5($modulePath, $nodePath, $type);

        return $nodeFileMd5 . '|cache/' . md5($modulePath);
    }

    public function getNodeFileMd5($modulePath, $nodePath, $type)
    {
        return md5(path($modulePath, '-', $nodePath, '.', $type));
    }

    public function getNodeFileContent($modulePath, $nodePath, $type)
    {
        if ($filePath = $this->getNodeFilePath($modulePath, $nodePath, $type)) {
            return read($filePath);
        }
    }

    public function getModuleRealPath($modulePath)
    {
        $modulesTree = \ewma\dev\Svc::getInstance()->getModulesTree();

        $moduleCache = ap($modulesTree, $modulePath);

       return ap($moduleCache, '-/settings/path');
    }

    public function getNodeFilePath($modulePath, $nodePath, $type)
    {
        if ($module = app()->modules->getByPath($this->getModuleRealPath($modulePath))) {
            $filePath = abs_path($module->dir, '-', $this->getTypeDir($type), $nodePath . '.' . $this->getTypeExtension($type));

            return $filePath;
        }
    }

    // models tree

    private function getModuleModelsTree($module)
    {
        return $this->getModuleModelsTreeRecursion($module->dir . '/-/models');
    }

    private function getModuleModelsTreeRecursion($modelsDir, $nodePathArray = [])
    {
        $nodePath = $nodePathArray ? '/' . implode('/', $nodePathArray) : '';

        if (is_dir($modelsDir . $nodePath)) {
            $nodes = scandir($modelsDir . $nodePath);

            $output = [];

            foreach ($nodes as $node) {
                if ($node != '.' && $node != '..') {
                    if (is_dir($modelsDir . $nodePath . '/' . $node)) {
                        $nodePathArray[] = $node;
                        $output[$node] = $this->getModuleModelsTreeRecursion($modelsDir, $nodePathArray);
                        array_pop($nodePathArray);
                    }

                    if (is_file($modelsDir . $nodePath . '/' . $node)) {
                        $path_info = pathinfo($modelsDir . $nodePath . '/' . $node);

                        $output['.'][] = $path_info['filename'];
                    }
                }
            }

            return $output;
        }
    }
}
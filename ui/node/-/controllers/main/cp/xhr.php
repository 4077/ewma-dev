<?php namespace ewma\dev\ui\node\controllers\main\cp;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function setType()
    {
        $this->s('~:type|', $this->data('type'), RR);

        $this->app->session->save($this->_module()->namespace);

        $this->e('ewma/dev/nodeEditor/typeSet/' . $this->_instance())->trigger();

        $this->c('~|')->performCallback('typeSelect', [
            'type' => $this->data('type')
        ]);
    }

    public function save()
    {
        $this->smap('~|', 'module_path, node_path, type');

        $svc = \ewma\dev\Svc::getInstance();

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];
        $type = $this->data['type'];

        $cache = &$this->d('~:' . $svc->getCachePath($modulePath, $nodePath, $type));

        $ownerId = $cache['user'] ?? false;

        if ($ownerId == $this->_user('id')) {
            $filePath = $svc->getNodeFilePath($modulePath, $nodePath, $type);

            write($filePath, $cache['value']);

            $cache = null;

            if ($type == 'js') {
                $this->jsReset();
            }

            if ($type == 'less') {
                $this->lessReset();
            }
        }

        $this->c('~:performCallback:update|');

        $this->e('ewma/dev/nodeEditor/save/' . $this->_instance())->trigger();
    }

    public function reset()
    {
        $this->smap('~|', 'module_path, node_path, type');

        $svc = \ewma\dev\Svc::getInstance();

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];
        $type = $this->data['type'];

        $cache = &$this->d('~:' . $svc->getCachePath($modulePath, $nodePath, $type));

        $ownerId = $cache['user'] ?? false;

        if ($ownerId == $this->_user('id')) {
            $cache = null;

            $this->c('~:performCallback:update|');

            $this->e('ewma/dev/nodeEditor/save/' . $this->_instance())->trigger();
        }
    }

    public function saveAll()
    {
        $this->smap('~|', 'module_path, node_path, type');

        $svc = \ewma\dev\Svc::getInstance();

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];

        $types = $svc->getNodeTypes($modulePath, $nodePath);

        foreach ($types as $type) {
            $cache = &$this->d('~:' . $svc->getCachePath($modulePath, $nodePath, $type));

            if ($cache) {
                $ownerId = $cache['user'] ?? false;

                if ($ownerId == $this->_user('id')) {
                    $filePath = $svc->getNodeFilePath($modulePath, $nodePath, $type);

                    write($filePath, $cache['value']);

                    $cache = null;

                    if ($type == 'js') {
                        $this->jsReset();
                    }

                    if ($type == 'less') {
                        $this->lessReset();
                    }
                }
            }
        }

        $this->c('~:performCallback:update|');

        $this->e('ewma/dev/nodeEditor/save/' . $this->_instance())->trigger();
    }

    public function delete()
    {
        $this->smap('~|', 'module_path, node_path, type');

        $svc = \ewma\dev\Svc::getInstance();

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];
        $type = $this->data['type'];

        $filePath = $svc->getNodeFilePath($modulePath, $nodePath, $type);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $this->d('~:' . $svc->getCachePath($modulePath, $nodePath, $type), null, RR);

        $this->c('\ewma~cache:reset', ['autoload' => true]);

        \ewma\dev\Svc::getInstance()->updateCache();

        $this->c('~|')->performCallback('update', [
            'module_path' => $modulePath,
            'node_path'   => $nodePath,
            'type'        => $type
        ]);

        $this->e('ewma/dev/nodeEditor/save/' . $this->_instance())->trigger();
    }


    public function deleteAll()
    {
        $this->smap('~|', 'module_path, node_path, type');

        $svc = \ewma\dev\Svc::getInstance();

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];

        $types = $svc->getNodeTypes($modulePath, $nodePath);

        foreach ($types as $type) {
            $cache = &$this->d('~:' . $svc->getCachePath($modulePath, $nodePath, $type));

            $locked = false;
            if ($cache) {
                $ownerId = $cache['user'] ?? false;

                if ($ownerId == $this->_user('id')) {
                    $locked = true;
                }
            }

            if (!$locked) {
                $filePath = $svc->getNodeFilePath($modulePath, $nodePath, $type);

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $cache = null;
            }
        }

        $this->c('\ewma~cache:reset', ['autoload' => true]);

        \ewma\dev\Svc::getInstance()->updateCache();

        $this->c('~|')->performCallback('update', [
            'module_path' => $modulePath,
            'node_path'   => $nodePath,
            'type'        => $this->data('type')
        ]);

        $this->e('ewma/dev/nodeEditor/save/' . $this->_instance())->trigger();
    }

    private function jsReset()
    {
        $this->c('\ewma~cache:reset', ['jsCompiler' => true]);
        $this->c('\ewma~js:increaseVersion');
    }

    private function lessReset()
    {
        $this->c('\ewma~cache:reset', ['cssCompiler' => true]);
        $this->c('\ewma~css:increaseVersion');
    }
}

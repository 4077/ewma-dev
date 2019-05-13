<?php namespace ewma\dev\ui\nodesTree\controllers\main;

class App extends \Controller
{
    public function onNodeFileCreate()
    {
        $this->setType();
    }

    public function setType()
    {
        $commonS = &$this->s('~');
        $s = &$this->s('~|');

        $modulePath = $this->data('module_path');
        $nodePath = $this->data('node_path');

        $s['node_path_by_module'][$modulePath] = $nodePath;
        $commonS['node_path'] = $nodePath;

        if ($type = $this->data('type')) {
            $s['type_by_module'][$modulePath] = $type;
            $commonS['type'] = $type;
        }

        $this->c('~|')->performCallback('select', [
            'module_path' => $modulePath,
            'node_path'   => $nodePath,
            'type'        => $commonS['type']
        ]);
    }
}

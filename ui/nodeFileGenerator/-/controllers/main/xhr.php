<?php namespace ewma\dev\ui\nodeFileGenerator\controllers\main;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function updateNodePath()
    {
        $this->s('~:node_path', $this->data('value'), RR);
    }

    public function generate()
    {
        $this->_smap('~', 'module_path, node_path');

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];
        $type = $this->data['type'];

        $template = $this->data['template'];

        $this->c('~template/' . $type . 'Generator:run', [
            'module_path' => $modulePath,
            'node_path'   => $nodePath,
            'template'    => $template
        ]);

        $this->c('~:reload', [], true);
    }

    public function updateCache()
    {
        \ewma\dev\Svc::getInstance()->updateCache();
    }
}

<?php namespace ewma\dev\ui\node\controllers\main\content\generateControl;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function generate()
    {
        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];
        $type = $this->data['type'];
        $template = $this->data['template'];

        $this->c('\ewma\dev\ui\nodeFileGenerator~template/' . $type . 'Generator:run', [
            'module_path' => $modulePath,
            'node_path'   => $nodePath,
            'template'    => $template
        ]);

        $this->c('~:reload|');
    }
}

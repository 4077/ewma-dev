<?php namespace dev\project\module\nav\controllers\input;

class Models_tree extends \Controller
{
    public $allow = self::XHR;

    public function toggle_subnodes()
    {
        $sMain = & $this->s('~');

        toggle($sMain['expand']['trees_by_modules']['models'][$this->data['module_path']], $this->data['model_path']);

        $this->c('controls/models_tree:reload');
    }
}
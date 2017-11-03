<?php namespace dev\project\controllers\input;

class Set_view extends \Controller
{
    public $allow = self::XHR;

    public function module()
    {
        if ($this->dataHas('module_path')) {
            $s = &$this->s('~');

            $s['content_view'] = 'module';
            $s['current_module_path'] = $this->data['module_path'];

            $this->c('main:reload');
        }
    }
}

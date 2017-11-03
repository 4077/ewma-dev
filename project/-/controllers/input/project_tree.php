<?php namespace dev\project\controllers\input;

class Project_tree extends \Controller
{
    public $allow = self::XHR;

    public function toggle_subnodes()
    {
        $sMain = &$this->s('~');

        toggle($sMain['expand_modules'], $this->data['module_path']);

        $this->c('views/project_tree:reload');
    }

    public function update_cache()
    {
        $this->c('services/project_tree:updateCache:' . $this->data['module_path']);

        $this->c('~:reload');
    }

    public function setScrollTop()
    {
        $s = &$this->s('views/project_tree');

        $s['scrollTop'] = $this->data('value');
    }
}

<?php namespace dev\project\module\generators\controllers;

class Input extends \Controller
{
    public $allow = self::XHR;

    //

    public function generate()
    {
        $s = &$this->s('~');

        $this->c('~:generate',
                 array(
                         'type'        => $this->data['type'],
                         'template'    => $this->data['template'],
                         'module_path' => $this->data['module_path'],
                         'node_path'  => $s['node_path']
                 ));

        $this->c('~:reload',
                 array(
                         'module_path' => $this->data['module_path'],
                         'node_path'  => $this->data['node_path']
                 ));

        $this->c('@nav~:reload');
        $this->c('@node~:reload');
    }

    //

    public function update_node_path()
    {
        $s = &$this->s('~');

        $s['node_path'] = $this->data['value'];
    }
}
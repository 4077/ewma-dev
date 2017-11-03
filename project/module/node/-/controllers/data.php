<?php namespace dev\project\module\node\controllers;

class Data extends \Controller
{
    public function get_session_node()
    {
        return $this->s($this->data['path']);
    }

    public function set_session_node()
    {
        $this->s($this->data['path'], $this->data['data'], true);
    }

    public function get_storage_node()
    {
        return $this->d($this->data['path']);
    }

    public function set_storage_node()
    {
        $d = &$this->d();

        $this->d($this->data['path'], $this->data['data'], true);
    }
}
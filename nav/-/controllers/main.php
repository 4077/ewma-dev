<?php namespace dev\nav\controllers;

class Main extends \Controller
{
    public function view()
    {
        $s = $this->s(false,
                      array(
                           'view' => 'project'
                      ));
    }
}
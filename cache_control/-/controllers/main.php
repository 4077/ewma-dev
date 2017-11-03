<?php namespace dev\cache_control\controllers;

class Main extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $this->css();

        $v->assign('CLEAR_MODULES_BUTTON', $this->c('\std\ui button:view',
                                                    array(
                                                         'path'    => 'input/main:clear:modules',
                                                         'class'   => 'clear_button modules',
                                                         'content' => 'Сбросить кеш модулей'
                                                    )));

        return $v;
    }
}
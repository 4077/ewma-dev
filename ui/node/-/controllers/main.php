<?php namespace ewma\dev\ui\node\controllers;

class Main extends \Controller
{
    public function __create()
    {
        $this->dmap('|', 'callbacks');
    }

    public function performCallback($name, $data = [])
    {
        if ($callback = $this->data('callbacks/' . $name)) {
            $this->_call($callback)->ra($data)->perform();
        }
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());

        $this->performCallback('reload');
    }

    public function view()
    {
        $v = $this->v('|');

        $this->d('|', ['cache' => []]);
        $this->s('|', ['type' => 'controller']);

        $this->smap('|', 'module_path, node_path, type');

        $v->assign([
                       'CP'      => $this->c('>cp:view|'),
                       'CONTENT' => $this->c('>content:view|')
                   ]);

        $this->css();

        $this->se('ewma/dev/nodeEditor/typeSet/' . $this->_instance())->rebind(':reload|');

        return $v;
    }
}

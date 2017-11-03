<?php namespace dev\project\module\nav\controllers\controls;

class Expand extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $this->css();

        $v->assign(array(
                           'EXPAND_CLASS' => $this->data['is_expand'] ? 'expand' : '',
                           'NAME'         => $this->data['name'],
                           'CONTENT'      => $this->data['content']
                   ));

        return $v;
    }
}
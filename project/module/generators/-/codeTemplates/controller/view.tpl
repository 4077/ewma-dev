<?php namespace {NAMESPACE};

class {CLASS_NAME} extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $v->assign([
                       'CONTENT' => false
                   ]);

        $this->css();

        return $v;
    }
}

<?php namespace dev\cp\controllers;

class Main extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $uis = $this->getUis();

        foreach ($uis as $name => $viewPath) {
            $v->assign('ui', [
                'NAME'    => $name,
                'CONTENT' => $this->c($viewPath)
            ]);
        }

        $this->css();

        return $v;
    }

    private function getUis()
    {
        return [
            'css' => '\ewma\ui\css~:view',
            'js'  => '\ewma\ui\js~:view',
        ];
    }
}

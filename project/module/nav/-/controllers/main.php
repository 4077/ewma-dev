<?php namespace dev\project\module\nav\controllers;

class Main extends \Controller
{
    private $controls = ['nodes_tree', 'models_tree'];

    private $control_names = [
        'nodes_tree'  => 'Nodes',
        'models_tree' => 'Models',
        'session'     => 'Session'
    ];

    public function reload()
    {
        $this->jquery('#dev_project_module_nav')->replace($this->view());
    }

    public function view()
    {
        $s = $this->s();
        $v = $this->v();

        $this->css();

        foreach ($this->controls as $control) {
            $vControl = $this->c('controls/' . $control . ':view');

            if (isset($s['expand']['sections'][$control])) {
                $v->assign('control', [
                    'CONTENT' => $this->c('controls/expand:view', [
                        'is_expand' => $s['expand']['sections'][$control],
                        'name'      => $this->control_names[$control],
                        'content'   => $vControl
                    ])
                ]);
            } else {
                $v->assign('control', [
                    'CONTENT' => $vControl
                ]);
            }
        }

        return $v;
    }
}
<?php namespace dev\project\controllers;

class Main extends \Controller
{
    public function reload()
    {
        $this->jquery('#dev_project')->replace($this->view());
    }

    public function view()
    {
        $this->c('session:init');

        $v = $this->v();

        $this->css();

        $v->assign([
                       'PROJECT_TREE' => $this->c('views/project_tree:render'),
                       'CONTENT'      => $this->content_view()
                   ]);

        return $v;
    }

    private function content_view()
    {
        $s = $this->s();

        if ($s['content_view'] == 'module') {
            return $this->c('module~:view');
        }
    }
}
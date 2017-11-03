<?php namespace dev\project\module\controllers;

class Main extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $this->css();

        $v->assign([
                       'NAV'     => $this->c('nav~:view'),
                       'CONTENT' => $this->content_view()
                   ]);

        return $v;
    }

    public function content_reload()
    {
        $this->jquery('#dev_projects_module_content_container')->html($this->content_view());
    }

    private function content_view()
    {
        $s = $this->s();

        if ($s['current_section'] == 'node') {
            return $this->c('node~:view');
        }

        if ($s['current_section'] == 'model') {
            return $this->c('model~:view');
        }
    }
}
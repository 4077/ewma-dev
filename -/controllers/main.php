<?php namespace dev\controllers;

class Main extends \Controller
{
    public function view()
    {
        $v = $this->v();

        $this->css();

        $v->assign('NAV', $this->c('nav~:view'));
        $v->append('NAV', $this->c('cache_control~:view'));

        $v->assign('CONTENT', $this->content_view());

        $this->c('\std\ui\dialogs~:addContainer:dev');

        $this->app->html->setFavicon(abs_url('-/ewma/favicons/dev_modules.png'));

        return $v;
    }

    public function content_view()
    {
        $sNav = $this->s('nav~');

        if ( $sNav['view'] == 'project' )
        {
            return $this->c('project~:view');
        }
    }
}

<?php namespace ewma\dev\ui\modulesTree\controllers\main;

class App extends \Controller
{
    public function onModuleCreate()
    {
        $this->app->modules->reload();

        \ewma\dev\Svc::getInstance()->updateCache();

        $dialogsContainer = $this->d('~:dialogs|');

        $this->c('\std\ui\dialogs~:close:createModule|' . $dialogsContainer);

        $this->c('~:reload|');
    }
}

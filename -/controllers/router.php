<?php namespace dev\controllers;

class Router extends \Controller
{
    public function getResponse()
    {
        $this->route('dev/cp')->to('cp~:view');

        $this->route('dev/routers')->to('/ewma/routers/ui~:view');
        $this->route('dev/routers/compile')->to('/ewma/routers~:compile');

        $this->route('dev/handlers')->to('/ewma/handlers/ui~:view');
        $this->route('dev/handlers/compileContainer/{container_id}')->to('/ewma/handlers~:compileContainer');
        $this->route('dev/handlers/renderContainer/{container_id}')->to('/ewma/handlers~:renderContainer');

        $this->route('dev*')->to('~:view');

        return $this->routeResponse();
    }
}
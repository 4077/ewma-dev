<?php namespace dev\cache_control\controllers\input;

class Main extends \Controller
{
    public $allow = self::XHR;

    public function clear($scope = false)
    {
        $this->c('/std cache:clear:' . $scope);
    }
}
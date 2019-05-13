<?php namespace ewma\dev\ui\controllers\main\cp;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function toggleLocation($location)
    {
        $modulesTreeS = &$this->s('modulesTree~:|');

        $localEnabled = &ap($modulesTreeS, 'display/local');
        $vendorEnabled = &ap($modulesTreeS, 'display/vendor');

        if ($location == 'local') {
            invert($localEnabled);

            if (!$localEnabled && !$vendorEnabled) {
                $vendorEnabled = true;
            }
        }

        if ($location == 'vendor') {
            invert($vendorEnabled);

            if (!$localEnabled && !$vendorEnabled) {
                $localEnabled = true;
            }
        }

        $this->c('<:reload|');
        $this->c('modulesTree~:reload|');
    }
}

<?php namespace ewma\dev\ui\controllers\main;

class Cp extends \Controller
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $modulesTreeS = $this->s('modulesTree~:|');

        $localEnabled = ap($modulesTreeS, 'display/local');
        $vendorEnabled = ap($modulesTreeS, 'display/vendor');

        $v->assign([
                       'TOGGLE_LOCAL_BUTTON'  => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleLocation:local|',
                           'class'   => 'toggle_location_button local ' . ($localEnabled ? 'enabled' : ''),
                           'content' => '<div class="icon">local</div>'
                       ]),
                       'TOGGLE_VENDOR_BUTTON' => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:toggleLocation:vendor|',
                           'class'   => 'toggle_location_button vendor ' . ($vendorEnabled ? 'enabled' : ''),
                           'content' => '<div class="icon">vendor</div>'
                       ])
                   ]);

        $this->css();

        return $v;
    }
}

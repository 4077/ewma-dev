<?php namespace {NAMESPACE};

class {CLASS_NAME} extends \Controller implements \ewma\Interfaces\RouterInterface
{
    public function getResponse()
    {
        $this->route('*')->to();

        return $this->routeResponse();
    }
}

<?php namespace ewma\dev\ui\createModule\controllers\main;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function updatePathTail()
    {
        $this->s('~:path_tail|', $this->data('value'), RR);

        $basePath = $this->d('~:module_path|');
        $pathTail = $this->s('~:path_tail|');

        $placeholder = $this->app->modules->dev->renderNamespace(path($basePath, $pathTail));

        $this->jquery($this->_selector('~:|') . " input.namespace")->attr('placeholder', $placeholder);
    }

    public function updateNamespace()
    {
        $this->s('~:namespace|', $this->data('value'), RR);
    }

    public function create($type = false)
    {
        $basePath = $this->d('~:module_path|');

        $this->smap('~|', 'path_tail, namespace');

        if ($pathTail = $this->data('path_tail')) {
            $path = path($basePath, $pathTail);

            app()->modules->dev->create($path, $this->data('namespace'), $type);

            $this->c('~|')->performCallback('create', [
                'base_path' => $basePath,
                'path_tail' => $pathTail,
                'path'      => $path
            ]);

            $this->s('~:path_tail|', false, RR);
        }
    }
}

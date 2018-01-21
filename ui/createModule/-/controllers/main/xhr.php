<?php namespace ewma\dev\ui\createModule\controllers\main;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function updatePathTail()
    {
        $this->s('~:path_tail|', $this->data('value'), RR);
    }

    public function create($type = false)
    {
        $basePath = $this->d('~:module_path|');

        $this->smap('~|', 'path_tail');

        if ($pathTail = $this->data('path_tail')) {
            $path = path($basePath, $pathTail);

            app()->modules->dev->create($path, false, $type);

            $this->c('~|')->performCallback('create', [
                'base_path' => $basePath,
                'path_tail' => $pathTail,
                'path'      => $path
            ]);

            $this->s('~:path_tail|', false, RR);
        }
    }
}

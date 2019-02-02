<?php namespace ewma\dev\ui\node\controllers\main\content;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function update()
    {
        $this->_smap('~|', 'module_path, node_path');

        $svc = \ewma\dev\Svc::getInstance();

        $cachePath = $svc->getCachePath(
            $this->data['module_path'],
            $this->data['node_path'],
            $this->data['type']
        );

        $cache = &$this->d('~:' . $cachePath);

        if (!isset($cache['user']) || $cache['user'] == $this->_user('id')) {
            ra($cache, [
                'user'  => $this->_user('id'),
                'value' => $this->data('value')
            ]);

            $this->se('ewma/dev/nodeEditor/update/' . $this->data('type') . '/' . $this->_instance())->trigger();
        } else {
            $this->c('~:reload|');
        }
    }
}

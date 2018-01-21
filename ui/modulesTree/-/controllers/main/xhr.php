<?php namespace ewma\dev\ui\modulesTree\controllers\main;

class Xhr extends \Controller
{
    public $allow = self::XHR;

    public function create()
    {
        $dialogsContainer = $this->d('~:dialogs|');

        $this->c('\std\ui\dialogs~:open:createModule|' . $dialogsContainer, [
            'path'          => '\ewma\dev\ui\createModule~:view|' . $this->_nodeInstance(),
            'data'          => [
                'module_path' => $this->data('module_path'),
                'callbacks'   => [
                    'create' => $this->_abs('@app:onModuleCreate|', [
                        'module_path' => $this->data('module_path')
                    ])
                ]
            ],
            'pluginOptions' => [
                'title'     => 'Create module',
                'resizable' => false
            ]
        ]);
    }

    public function delete()
    {
        $dialogsContainer = $this->d('~:dialogs|');

        if ($this->data('discarded')) {
            $this->c('\std\ui\dialogs~:close:deleteConfirm|' . $dialogsContainer);
        } else {
            $modulePath = $this->data('module_path');

            if ($this->data('confirmed')) {
                $this->app->modules->dev->delete($modulePath);
                $this->app->modules->reload();

                \ewma\dev\Svc::getInstance()->updateCache();

                $dialogsContainer = $this->d('~:dialogs|');

                $this->c('\std\ui\dialogs~:close:deleteConfirm|' . $dialogsContainer);

                $this->c('~:reload|');
            } else {
                $this->c('\std\ui\dialogs~:open:deleteConfirm|' . $dialogsContainer, [
                    'path' => '\std dialogs/confirm~:view',
                    'data' => [
                        'confirm_call' => $this->_abs(':delete|', ['module_path' => $modulePath]),
                        'discard_call' => $this->_abs(':delete|', ['module_path' => $modulePath]),
                        'message'      => 'Delete module <b>' . $modulePath . '</b>?'
                    ]
                ]);
            }
        }
    }

    public function toggleSubnodes()
    {
        $s = &$this->s('~|');

        toggle($s['expand_nodes'], $this->data['module_path']);

        $this->c('~:reload|');
    }

    public function selectModule()
    {
        $s = &$this->s('~|');

        $s['selected_module_path'] = $this->data('module_path');

        $this->c('<|')->performCallback('select', [
            'module_path' => $this->data('module_path')
        ]);
    }
}

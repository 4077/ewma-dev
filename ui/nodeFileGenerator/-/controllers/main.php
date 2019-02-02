<?php namespace ewma\dev\ui\nodeFileGenerator\controllers;

class Main extends \Controller
{
    public function __create()
    {
        $this->dmap('|', 'callbacks');
    }

    public function performCallback($name, $data = [])
    {
        if ($callback = $this->data('callbacks/' . $name)) {
            $this->_call($callback)->ra($data)->perform();
        }
    }

    public function reload()
    {
        $this->jquery()->replace($this->view());
    }

    public function view()
    {
        $v = $this->v();

        $this->smap(false, 'module_path, node_path');

        $modulePath = $this->data['module_path'];
        $nodePath = $this->data['node_path'];

        if ($module = $this->app->modules->getByPath($modulePath)) {
            $v->assign([
                           'MODULE_PATH'         => $modulePath,
                           'NODE_PATH'           => $nodePath,
                           'UPDATE_CACHE_BUTTON' => $this->c('\std\ui button:view', [
                               'path'  => '>xhr:updateCache',
                               'class' => 'update_cache_button',
                               'icon'  => 'fa fa-refresh'
                           ])
                       ]);

            foreach ($this->getTypes() as $type) {
                $v->assign('type', [
                    'NAME' => $type
                ]);

                foreach ($this->getTemplates($type) as $template) {
                    $v->assign('type/template', [
                        'BUTTON' => $this->c('\std\ui button:view', [
                            'path'    => '>xhr:generate',
                            'data'    => [
                                'type'     => $type,
                                'template' => $template,
                            ],
                            'class'   => 'generate_button ' . $type,
                            'content' => $template
                        ])
                    ]);
                }
            }

            $this->c('\std\ui liveinput:bind', [
                'selector' => $this->_selector('|') . ' .path .node_path input',
                'path'     => '>xhr:updateNodePath|'
            ]);
        } else {
            $this->console('Not found module with path ' . $this->data['module_path']);
        }

        $this->css(':\ewma\dev\ui nodeTypesColors');

        return $v;
    }

    public function getTypes()
    {
        return ['controller', 'js', 'css', 'less', 'template'];
    }

    public function getTemplates($type = false)
    {
        $templates = [
            'controller' => l2a('view_reload_instance, app, xhr, svc, router, view, view_reload'),
            'js'         => l2a('widget, widget_setOption, object, plugin'),
            'css'        => [],
            'less'       => l2a('empty, empty_instance'),
            'template'   => l2a('empty_instance, empty'),
        ];

        if ($type) {
            return $templates[$type];
        } else {
            return $templates;
        }
    }
}

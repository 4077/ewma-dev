<?php namespace dev\project\module\generators\controllers;

class Main extends \Controller
{
    public function reload()
    {
        $this->jquery('#dev_project_module_generators')->replace($this->view());
    }

    public function view()
    {
        $s = &$this->s(false, [
            'node_path' => ''
        ]);

        if (!$s['node_path'] && isset($this->data['node_path'])) {
            $s['node_path'] = $this->data['node_path'];
        }

        $v = $this->v();

        $v->assign([
                       'MODULE_NAMESPACE' => $this->app->modules->getByPath($this->data['module_path'])->namespace,
                       'NODE_PATH'        => $s['node_path'],
                   ]);

        foreach ($this->get_templates_types() as $type) {
            $v->assign('type', [
                'NAME' => $type
            ]);

            foreach ($this->get_templates($type) as $template) {
                $v->assign('type/template', [
                    'BUTTON' => $this->c('\std\ui button:view', [
                        'path'    => 'input:generate',
                        'data'    => [
                            'type'        => $type,
                            'template'    => $template,
                            'module_path' => $this->data['module_path'],
                            'node_path'   => $this->data['node_path'],
                        ],
                        'class'   => 'generate_button ' . $type,
                        'content' => $template
                    ])
                ]);
            }
        }

        $this->js(':dev_project_module_generators.bind', [
            'paths' => [
                'update_node_path' => $this->_p('input:update_node_path')
            ]
        ]);

        $this->css();

        return $v;
    }

    public function get_templates_types()
    {
        return ['controller', 'js', 'css', 'less', 'template'];
    }

    public function get_templates($type = false)
    {
        $templates = [
            'controller' => l2a('app, xhr, svc, router, view, view_reload, view_reload_instance'),
            'js'         => l2a('object, jquery_plugin, widget, widget_full'),
            'css'        => [],
            'less'       => l2a('empty, empty_instance'),
            'template'   => l2a('empty, empty_instance'),
        ];

        if ($type) {
            return $templates[$type];
        } else {
            return $templates;
        }
    }

    public function generate()
    {
        $type = $this->data['type'];
        $template = $this->data['template'];
        $module_path = $this->data['module_path'];
        $node_path = $this->data['node_path'];

        $this->c('templates/_' . $type . ':write', [
            'template'    => $template,
            'module_path' => $module_path,
            'node_path'   => $node_path
        ]);

        $this->c('^services/project_tree:updateCache:' . $module_path);
    }

    public function reset_node_path()
    {
        $s = &$this->s();

        $s['node_path'] = false;
    }
}
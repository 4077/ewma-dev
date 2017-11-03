<?php namespace dev\project\module\node\controllers\controls;

class Generate extends \Controller
{
    public $allow = [self::XHR, self::APP];

    public function generate()
    {
        $this->c('@generators~:generate',
                 array(
                         'type'        => $this->data['type'],
                         'template'    => $this->data['template'],
                         'module_path' => $this->data['module_path'],
                         'node_path'   => $this->data['node_path']
                 ));

        $this->c('~:reload');

        $this->c('@nav controls/nodes_tree:reload');
    }

    public function view()
    {
        $type = $this->data['type'];

        //

        $v = $this->v();

        $templates = $this->c('@generators~:get_templates:' . $type);

        foreach ( $templates as $template )
        {
            $v->assign('template',
                       array(
                               'BUTTON' => $this->c('\std\ui button:view',
                                                    array(
                                                            'path'    => ':generate',
                                                            'data'    => array(
                                                                    'type'        => $type,
                                                                    'template'    => $template,
                                                                    'module_path' => $this->data['module_path'],
                                                                    'node_path'   => $this->data['node_path']
                                                            ),
                                                            'class'   => 'create_template_button ' . $type,
                                                            'content' => $template
                                                    ))
                       ));
        }

        $this->css();

        return $v;
    }
}
<?php namespace dev\project\module\node\controllers\views;

class Tabs extends \Controller
{
    private $views = ['controller', 'js', 'css', 'less', 'template', 'session', 'storage'];

    public function render()
    {
        $v = $this->v();

        $this->css();

        $views = $this->c('^services/nodes_tree')->get_node_types($this->data['module_path'], $this->data['node_path']);

        foreach ($this->views as $view) {
            $class = 'tab ' . $view;

            if (in_array($view, $views)) {
                $class .= ' has_file';
            }

            // todo сессию и сторадж подсвечивать только если не пустые

            if ($this->data['node_view'] == $view) {
                $class .= ' selected';
            }

            $v->assign('tab', [
                'BUTTON' => $this->c('\std\ui button:view', [
                    'path'    => '^module input/set_view:node',
                    'data'    => [
                        'node_path' => $this->data['node_path'],
                        'node_view' => $view
                    ],
                    'class'   => $class,
                    'content' => $view
                ])
            ]);
        }

        return $v;
    }
}
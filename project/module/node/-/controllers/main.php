<?php namespace dev\project\module\node\controllers;

class Main extends \Controller
{
    private $module;

    private $modulePath;

    private $nodePath;

    private $view;

    public function __create()
    {
        $s = $this->s();
        $sProjectMain = $this->s('^');

        $this->modulePath = $sProjectMain['current_module_path'];
        $this->module = $this->app->modules->getByPath($this->modulePath);

        $this->view = $s['current_view'];

        if (isset($s['current_path_by_module'][$this->modulePath])) {
            $this->nodePath = $s['current_path_by_module'][$this->modulePath];
        } else {
            $this->nodePath = $s['current_path_through_project'];
            $this->nodePath = 'main';
        }
    }

    //

    public function reload()
    {
        $this->jquery('#dev_project_module_node')->replace($this->view());
    }

    public function view()
    {
        if ($this->nodePath) {
            $v = $this->v();

            $v->assign([
//                           'TABS'    => $this->c('views/tabs:render', [
//                               'module_path' => $this->modulePath,
//                               'node_path'   => $this->nodePath,
//                               'node_view'   => $this->view
//                           ]),
'CONTENT' => $this->c('\ewma\dev\ui\node~:view|' . $this->_nodeInstance(), [
    'module_path' => $this->modulePath,
    'node_path'   => $this->nodePath,
    'type'        => $this->view,
    'callbacks'   => [
        'typeSelect' => $this->_abs(':onTypeSelect|', [
            'node_path' => $this->nodePath
        ]),
        //                'reload' => $this->_abs(':onNodeReload|', $this->data),
        //                'update' => $this->_abs(':onUpdate|', $this->data)
    ]
])
                       ]);

            return $v;
        }
    }

    public function onTypeSelect()
    {
        $this->c('^module input/set_view:node', [
            'node_path' => $this->data['node_path'],
            'node_view' => $this->data('type')
        ]);
    }

    private function content_view()
    {
        $v = $this->v('views/content');

        if (in_array($this->view, ['controller', 'js', 'css', 'less', 'template'])) {
            $code = $this->getFileContent();

            if ($code) {
                $v->assign([
                               'CONTENT' => $this->editor_view($code)
                           ]);
            } else {
                $v->assign([
                               'CONTENT' => $this->create_control_view($this->view)
                           ]);
            }
        }

        if ($this->view == 'session') {
            $v->assign([
                           'CONTENT' => $this->c('views/session:view', [
                               'module_path' => $this->modulePath,
                               'node_path'   => $this->nodePath
                           ])
                       ]);
        }

        if ($this->view == 'storage') {
            $v->assign([
                           'CONTENT' => $this->c('views/storage:view', [
                               'module_path' => $this->modulePath,
                               'node_path'   => $this->nodePath
                           ])
                       ]);
        }

        return $v;
    }

    private function editor_view($code)
    {
        return $this->c('\ewma\dev\ui\node~:view|' . $this->_nodeInstance(), [
            'module_path' => $this->modulePath,
            'node_path'   => $this->nodePath,
            'callbacks'   => [
//                'reload' => $this->_abs(':onNodeReload|', $this->data),
//                'update' => $this->_abs(':onUpdate|', $this->data)
            ]
        ]);

//        $id = k(8);
//
//        $this->c('\ace~:bind',
//                 [
//                     'container_id' => $id,
//                     'mode'         => $this->getEditorMode(),
//                     'code'         => $code
//                 ]);
//
//        $this->js('editor:dev_project_module_editor.bind',
//                  [
//                      'container_id' => $id
//                  ]);
//
////        $this->c('/plugins/perfect_scrollbar~:bind',
////                 array(
////                         'selector' => '#' . $id . ' .ace_scrollbar-v',
////                         'options'  => array(
////                                 'useBothWheelAxes' => true
////                         )
////                 ));
////
////        $this->c('/plugins/perfect_scrollbar~:bind',
////                 array(
////                         'selector' => '#' . $id . ' .ace_scrollbar-h',
////                         'options'  => array(
////                                 'useBothWheelAxes' => true
////                         )
////                 ));
//
//        return $this->c('\std\ui tag:view', [
//            'attrs' => [
//                'id'    => $id,
//                'style' => 'position: absolute; height: 600px; width: 100%;'
//            ]
//        ]);
    }

    private function create_control_view($type)
    {
        return $this->c('controls/generate:view', [
            'type'        => $type,
            'module_path' => $this->modulePath,
            'node_path'   => $this->nodePath
        ]);
    }

    //

    private $dataByType = [

        // [type => dir, ext, editor_mode]

        'controller' => ['controllers', 'php', 'php'],
        'js'         => ['js', 'js', 'javascript'],
        'css'        => ['css', 'css', 'css'],
        'less'       => ['less', 'less', 'less'],
        'template'   => ['templates', 'tpl', 'smarty']
    ];

    private function getTypeDir()
    {
        return $this->dataByType[$this->view][0];
    }

    private function getExtension()
    {
        return $this->dataByType[$this->view][1];
    }

    private function getEditorMode()
    {
        return $this->dataByType[$this->view][2];
    }

    //

    private function getFileContent()
    {
        $filePath = $this->getFilePath();

        return read($filePath);
    }

    private function getFilePath()
    {
        $modulesDir = $this->module->location == 'local'
            ? 'modules'
            : 'modules-vendor';

        $filePath = abs_path($this->modulePath ? $modulesDir . '/' . $this->modulePath : '', '-', $this->getTypeDir(), $this->nodePath . '.' . $this->getExtension());

        return $filePath;
    }
}

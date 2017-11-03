<?php namespace dev\project\module\model\controllers;

class Main extends \Controller
{
    private $modulePath;
    private $path;

    public function __create()
    {
        $s = $this->s();
        $sProjectMain = $this->s('^');

        $this->modulePath = $sProjectMain['current_module_path'];

        if (isset($s['current_path_by_module'][$this->modulePath])) {
            $this->path = $s['current_path_by_module'][$this->modulePath];
        }
    }

    //

    private function getFileContent()
    {
        return read($this->getFilePath());
    }

    private function getFilePath()
    {
        $moduleDirPath = $this->modulePath ? path('modules', $this->modulePath) : '';
        $modelFilePath = abs_path($moduleDirPath, '-/models', $this->path . '.php');

        return $modelFilePath;
    }

    //

    public function reload()
    {
        $this->jquery('#dev_project_module_model')->replace($this->view());
    }

    public function view()
    {
        if ($this->path) {
            $v = $this->v();

            $v->assign([
                           'CONTENT' => $this->content_view()
                       ]);

            return $v;
        }
    }

    private function content_view()
    {
        $v = $this->v('views/content');

        $v->assign([
                       'CONTENT' => $this->editor_view()
                   ]);

        return $v;
    }

    private function editor_view()
    {
        $s = $this->s();

        $id = k();

        $this->c('\ace~:bind',
                 [
                     'container_id' => $id,
                     'mode'         => 'php',
                     'code'         => $this->getFileContent()
                 ]);

        $this->js('editor:dev_project_module_editor.bind',
                  [
                      'container_id' => $id
                  ]);

        return $this->c('\std\ui tag:view',
                        [
                            'attrs'   => [
                                'id'    => $id,
                                'style' => 'position: absolute; height: 600px; width: 100%;'
                            ],
                            'content' => '' //$this->get_file_content()
                        ]);
    }
}
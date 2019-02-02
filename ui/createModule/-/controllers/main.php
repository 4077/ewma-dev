<?php namespace ewma\dev\ui\createModule\controllers;

class Main extends \Controller
{
    public function __create()
    {
        $this->dmap('|', 'module_path, callbacks');
    }

    public function performCallback($name, $data)
    {
        $callbacks = $this->data('callbacks');

        if (isset($callbacks[$name])) {
            $this->_call($callbacks[$name])->ra($data)->perform();
        }
    }

    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $basePath = $this->data('module_path');
        $namespace = $this->s(':namespace|');
        $pathTail = $this->s(':path_tail|');

        $v->assign([
                       'BASE_PATH'               => $basePath,
                       'PATH_TAIL'               => $pathTail,
                       'NAMESPACE'               => $namespace,
                       'AUTO_NAMESPACE'          => $namespace ? false : $this->app->modules->dev->renderNamespace(path($basePath, $pathTail)),
                       'CREATE_SLAVE_BUTTON'     => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:create:slave|',
                           'class'   => 'create_button slave',
                           'content' => 'slave'
                       ]),
                       'CREATE_MASTER_BUTTON'    => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:create:master|',
                           'class'   => 'create_button master',
                           'content' => 'master'
                       ]),
                       'CREATE_INHERITED_BUTTON' => $this->c('\std\ui button:view', [
                           'path'    => '>xhr:create|',
                           'class'   => 'create_button inherited',
                           'content' => 'inherited'
                       ])
                   ]);

        $this->c('\std\ui liveinput:bind', [
            'selector' => $this->_selector('|') . ' .path input.tail',
            'path'     => '>xhr:updatePathTail|'
        ]);

        $this->c('\std\ui liveinput:bind', [
            'selector' => $this->_selector('|') . ' .path input.namespace',
            'path'     => '>xhr:updateNamespace|'
        ]);

        $this->css(':\css\std~');

        $this->widget(':', [
            'paths' => [
                'create'          => $this->_p('>xhr:create|'),
                'updateNamespace' => $this->_p('>xhr:updateNamespace|'),
            ]
        ]);

        return $v;
    }
}

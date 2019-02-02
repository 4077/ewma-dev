<?php namespace ewma\dev\ui\node\controllers\main;

class Content extends \Controller
{
    public function reload()
    {
        $this->jquery('|')->replace($this->view());
    }

    public function view()
    {
        $v = $this->v('|');

        $this->smap('~|', 'module_path, node_path, type');

        $type = $this->data['type'];

        if (in($type, 'controller, js, css, less, template')) {
            $readonly = false;

            if ($cache = $this->getCache()) {
                $code = $cache['value'];
                $user = $cache['user'];

                $readonly = $user != $this->_user('id');
            } else {
                $code = $this->getCode();
            }

            $content = $code
                ? $this->editorView($code, $readonly)
                : $this->generateControlView();
        }

        if ($type == 'session' || $type == 'storage') {
            $content = $this->c('>data:view|');
        }

        $v->assign([
                       'CONTENT' => $content ?? ''
                   ]);

        $this->css();

        return $v;
    }

    private function editorView($code, $readonly)
    {
        return $this->c('\js\ace~:view|' . $this->_nodeInstance(), [
            'path'     => '>xhr:update|',
            'data'     => [
                'type' => $this->data['type']
            ],
            'mode'     => $this->getEditorMode(),
            'code'     => $code,
            'readonly' => $readonly
        ]);
    }

    private function generateControlView()
    {
        return $this->c('>generateControl:view|', [
            'module_path' => $this->data['module_path'],
            'node_path'   => $this->data['node_path'],
            'type'        => $this->data['type']
        ]);
    }

    private function getCache()
    {
        $cachePath = \ewma\dev\Svc::getInstance()->getCachePath(
            $this->data['module_path'],
            $this->data['node_path'],
            $this->data['type']
        );

        return $this->d('~:' . $cachePath);
    }

    private function getCode()
    {
        return \ewma\dev\Svc::getInstance()->getNodeFileContent(
            $this->data['module_path'],
            $this->data['node_path'],
            $this->data['type']
        );
    }

    private function getEditorMode()
    {
        $modes = [
            'controller' => 'php',
            'js'         => 'javascript',
            'css'        => 'css',
            'less'       => 'less',
            'template'   => 'smarty'
        ];

        return $modes[$this->data['type']] ?? 'txt';
    }
}

<?php namespace ewma\dev\ui\controllers;

class Node extends \Controller
{
    public function reload()
    {
        $this->jquery()->replace($this->view());
    }

    public function view()
    {
        $v = $this->v();

        $path = $this->app->request->data('path');

        $this->instance_($path);

        $this->smap('|', 'type');

        if ($type = $this->app->request->data('type')) {
            $this->s(':type|', $type, RR);
        }

        list($modulePath, $nodePath) = $this->app->paths->separateAbsPath($path);

        $v->assign([
                       'CONTENT' => $this->c('\ewma\dev\ui\node~:view|' . $this->_nodeInstance(), [
                           'module_path' => $modulePath,
                           'node_path'   => $nodePath,
                           'type'        => $this->s(':type|'),
                           'callbacks'   => [
                               'typeSelect' => $this->_abs('>app:onSelectNode|'),
                               'update'     => $this->_abs('>app:onUpdate|')
                           ]
                       ])
                   ]);

        $this->css();

        return $v;
    }
}

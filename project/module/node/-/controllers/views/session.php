<?php namespace dev\project\module\node\controllers\views;

use ewma\models\Session as SessionModel;

class Session extends \Controller
{
    public $allow = [self::XHR, self::APP];

    public function reload()
    {
        $this->jquery('#dev_project_module_node_session')->replace($this->view());
    }

    public function view()
    {
        $instance = $this->data['module_path'] . '/_/' . $this->data['node_path'];

        $v = $this->v();
        $s = $this->s(':|' . $instance, [
            'selected_instance' => ''
        ]);

        $nodeInstances = $this->getNodeInstances();

        foreach ($nodeInstances as $nodeInstance) {

            $requestData = $this->data;
            $requestData['node_instance'] = $nodeInstance;
            $v->assign('instance', [
                'SELECT_BUTTON' => $this->c('\std\ui button:view', [
                    'path'    => ':selectInstance',
                    'data'    => $requestData,
                    'content' => ($nodeInstance ? $nodeInstance : '----') . ($s['selected_instance'] == $nodeInstance ? '<----' : '')
                ])
            ]);
        }

        $v->assign([
                       'JEDIT' => $this->c('\std\ui\data~:view|dev/sessions/_/' . $instance . '/_/' . $s['selected_instance'], [
                           'read_call'  => $this->_abs(':readNode', [
                               'path' => '/' . $this->data['module_path'] . ' ' . $this->data['node_path'] . ':|' . $s['selected_instance']
                           ]),
                           'write_call' => $this->_abs(':writeNode', [
                               'path' => '/' . $this->data['module_path'] . ' ' . $this->data['node_path'] . ':|' . $s['selected_instance']
                           ]),
                           'expand'     => true
                       ])
                   ]);

        return $v;
    }

    public function selectInstance()
    {
        $instance = $this->data['module_path'] . '/_/' . $this->data['node_path'];

        $s = &$this->s(':|' . $instance);

        $s['selected_instance'] = $this->data['node_instance'];

        $this->reload();
    }

    private function getNodeInstances()
    {
        $module = $this->app->modules->getByPath($this->data['module_path']);

        $moduleNamespace = $module->namespace;

        $nodeInstances = SessionModel::where('module_namespace', $moduleNamespace)
            ->where('node_path', $this->data['node_path'])
            ->where('key', $this->app->session->getKey())// self session // todo other users sessions
            ->orderBy('node_instance')
            ->get();

        $output = [];
        foreach ($nodeInstances as $nodeInstance) {
            $output[] = $nodeInstance['node_instance'];
        }

        return $output;
    }

    public function readNode()
    {
        return $this->s($this->data['path']);
    }

    public function writeNode()
    {
        $this->s($this->data['path'], $this->data['data'], true);
    }
}
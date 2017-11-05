<?php namespace dev\project\module\generators\controllers\templates;

abstract class Template extends \Controller
{
    protected $module_path;
    protected $node_path;
    protected $template;
    protected $type;

    public function __create()
    {
        $this->module_path = $this->data['module_path'];
        $this->node_path = $this->data['node_path'];
        $this->template = $this->data['template'];
    }

    abstract public function get_write_path();

    abstract public function get_code();

    public function get_template()
    {
        return read(abs_path($this->_nodeFilePath(path($this->type, $this->template) . '.tpl', 'codeTemplates')));
    }

    public function get_module_path()
    {
        if ($module = $this->app->modules->getByPath($this->module_path)) {
            return $module->getDir();
        }
    }

    public function get_node_id()
    {
        return str_replace('\\', '_', $this->get_module()->namespace) . '__' . str_replace('/', '_', $this->node_path);
    }

    private function get_module()
    {
        return $this->app->modules->getByPath($this->module_path);
    }

    public function write()
    {
        $write_path = $this->get_write_path();

        if ($write_path && !file_exists($write_path)) {
            write($write_path, $this->get_code());
        }
    }
}

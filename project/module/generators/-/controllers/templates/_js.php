<?php namespace dev\project\module\generators\controllers\templates;

class _js extends Template
{
    protected $type = 'js';

    public function get_code()
    {
        $template = $this->get_template();

        $code = str_replace(array('{NAMESPACE}', '{NODE_ID}'),
                            array($this->get_namespace(), $this->get_node_id()),
                            $template);

        return $code;
    }

    public function get_write_path()
    {
        return abs_path($this->get_module_path(), '-/js', $this->node_path . '.js');
    }

    public function get_namespace()
    {
        return str_replace('/', '_', $this->module_path);
    }
}

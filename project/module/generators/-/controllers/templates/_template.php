<?php namespace dev\project\module\generators\controllers\templates;

class _template extends Template
{
    protected $type = 'template';

    public function get_code()
    {
        $template = $this->get_template();

        $code = str_replace(['{NODE_ID}'],
                            [$this->get_node_id()],
                            $template);

        return $code;
    }

    public function get_write_path()
    {
        return abs_path($this->get_module_path(), '-/templates', $this->node_path . '.tpl');
    }
}

<?php namespace dev\project\module\generators\controllers\templates;

class _less extends Template
{
    protected $type = 'less';

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
        return abs_path($this->get_module_path(), '-/less', $this->node_path . '.less');
    }
}
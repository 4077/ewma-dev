<?php namespace dev\project\module\generators\controllers\templates;

class _controller extends Template
{
    protected $type = 'controller';

    public function get_code()
    {
        $template = $this->get_template();

        $code = str_replace(['{NAMESPACE}', '{CLASS_NAME}', '{NODE_ID}'],
                            [$this->get_namespace(), $this->get_class_name(), $this->get_node_id()],
                            $template);

        return $code;
    }

    public function get_write_path()
    {
        return abs_path($this->get_module_path(), '-/controllers', $this->node_path . '.php');
    }

    public function get_namespace()
    {
        $settingsFilePath = $this->get_module_path() . '/settings.php';
        if (file_exists($settingsFilePath)) {
            $module_settings = require $this->get_module_path() . '/settings.php';
        } else {
            $module_settings = [
                'namespace' => ''
            ];
        }

        $node_path_array = p2a($this->node_path);
        $node_namespace = implode('\\', array_slice($node_path_array, 0, -1));

        return $module_settings['namespace'] . '\controllers' . ($node_namespace ? '\\' . $node_namespace : '');
    }

    public function get_class_name()
    {
        $node_path_array = p2a($this->node_path);

        $class_name = end($node_path_array);
        $class_name = strtoupper(substr($class_name, 0, 1)) . substr($class_name, 1);

        return $class_name;
    }
}

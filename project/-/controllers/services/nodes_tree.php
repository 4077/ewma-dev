<?php namespace dev\project\controllers\services;

class Nodes_tree extends \Controller
{
    public $singleton = true;

    public function get_nodes_tree($modulePath = false)
    {
        $projectTree = $this->c('@project_tree:getProjectTree');
        $module = ap($projectTree, $modulePath);

        return $module['-']['nodes'];
    }

    public function get_node_types($modulePath, $nodePath = false)
    {
        $projectTree = $this->c('@project_tree:getProjectTree');
        $module = ap($projectTree, $modulePath);

        if ($nodePath) {
            $node = ap($module, '-/nodes/' . $nodePath);
        }

        $types = [];

        if (isset($node['.'])) {
            $exts = array_keys($node['.']);

            foreach ($exts as $ext) {
                if ($ext == 'php') {
                    $type = 'controller';
                } elseif ($ext == 'tpl') {
                    $type = 'template';
                } else {
                    $type = $ext;
                }

                $types[] = $type;
            }
        }

        return $types;
    }
}
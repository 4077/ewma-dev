<?php namespace ewma\dev\ui\nodeFileGenerator\controllers\main\template;

use ewma\dev\ui\nodeFileGenerator\controllers\main\AbstractGeneratorController;

class ControllerGenerator extends AbstractGeneratorController
{
    protected $type = 'controller';

    public function getReplacements()
    {
        return [
            'NAMESPACE'  => $this->getNamespace(),
            'CLASS_NAME' => $this->getClassName(),
            'NODE_ID'    => $this->getNodeId(),
        ];
    }

    public function getNamespace()
    {
        $settingsFilePath = $this->getModuleDir() . '/settings.php';
        if (file_exists($settingsFilePath)) {
            $moduleSettings = require $this->getModuleDir() . '/settings.php';
        } else {
            $moduleSettings = [
                'namespace' => ''
            ];
        }

        $nodePathArray = p2a($this->nodePath);
        $nodeNamespace = implode('\\', array_slice($nodePathArray, 0, -1));

        return $moduleSettings['namespace'] . '\controllers' . ($nodeNamespace ? '\\' . $nodeNamespace : '');
    }

    public function getClassName()
    {
        $nodePathArray = p2a($this->nodePath);

        $className = end($nodePathArray);
        $className = ucfirst($className);

        return $className;
    }
}

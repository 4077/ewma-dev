<?php namespace ewma\dev\ui\nodeFileGenerator\controllers\main;

abstract class AbstractGeneratorController extends \Controller
{
    protected $modulePath;

    protected $nodePath;

    protected $template;

    protected $type;

    public function __create()
    {
        $this->modulePath = $this->data['module_path'];
        $this->nodePath = $this->data['node_path'];
        $this->template = $this->data['template'];
    }

    public function run()
    {
        $filePath = $this->getTargetFilePath();

        if ($filePath && !file_exists($filePath)) {
            write($filePath, $this->render());

            $this->c('\ewma~cache:reset', ['autoload' => true]);

            $this->c('~')->performCallback('generate', [
                'module_path' => $this->modulePath,
                'node_path'   => $this->nodePath,
                'type'        => $this->type
            ]);
        }
    }

    public function getTargetFilePath()
    {
        return \ewma\dev\Svc::getInstance()->getNodeFilePath(
            $this->modulePath,
            $this->nodePath,
            $this->type
        );
    }

    public function render()
    {
        $template = $this->getTemplate();

        $code = \ewma\Data\Data::tokenize($template, $this->getReplacements());

        return $code;
    }

    public function getReplacements()
    {
        return [];
    }

    public function getTemplate()
    {
        return read(abs_path($this->_nodeFilePath(path($this->type, $this->template) . '.tpl', 'data/codeTemplates')));
    }

    public function getModuleDir()
    {
        if ($module = $this->app->modules->getByPath($this->modulePath)) {
            return $module->dir;
        }
    }

    public function getNodeId()
    {
        return str_replace('\\', '_', $this->getModule()->namespace) . '__' . str_replace('/', '_', $this->nodePath);
    }

    private function getModule()
    {
        return $this->app->modules->getByPath($this->modulePath);
    }
}

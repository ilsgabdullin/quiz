<?php
namespace app\core;

class Controller
{

    private $_id;

    public $model;
    public $view;
    public $defaultAction = 'index';

    function __construct($id)
    {
        $this->view = new View();
        $this->_id = $id;
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getActionMethod($action)
    {
        if (!$action)
            $action = $this->defaultAction;

        $action = 'action' . ucfirst($action);

        return $action;
    }

    public function getViewPath($view)
    {
        if (!is_string($view))
            return;

        if ($view[0] != '/')
            $view = $this->getId() . DIRECTORY_SEPARATOR . $view;

        return $view . '.php';
    }

    public function render($view, $data = [])
    {
        $view = $this->getViewPath($view);
        $this->view->generate($view, $data);
    }
}

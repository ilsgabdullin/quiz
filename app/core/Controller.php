<?php
namespace app\core;

/**
 * Класс контроллера.
 * @package app\core
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */
class Controller
{
    /**
     * @var View ссылка на объект для работы с представлением
     */
    public $view;
    /**
     * @var string действие по умолчанию
     */
    public $defaultAction = 'index';
    /**
     * @var string название контроллера
     */
    private $_id;

    /**
     * Конструктор.
     * @param string $id название контроллера
     */
    function __construct($id)
    {
        $this->view = new View();
        $this->_id = $id;
    }

    /**
     * Формирует полное название метода с действием.
     * @param string $action название метода
     * @return string полное название метода действия
     */
    public function getActionMethod($action)
    {
        if (!$action) {
            $action = $this->defaultAction;
        }

        $action = 'action' . ucfirst($action);

        return $action;
    }

    /**
     * Выводит представление.
     * @param string $view название файла с представлением
     * @param array $data данные для передачи в представление
     */
    public function render($view, $data = [])
    {
        $view = $this->getViewPath($view);
        $this->view->generate($view, $data);
    }

    /**
     * Возвращает путь к представлению.
     * @param string $view название файла с представленеим
     * @return bool|string путь к файлу с представлением
     */
    public function getViewPath($view)
    {
        if (!is_string($view)) {
            return false;
        }

        if ($view[0] != '/') {
            $view = $this->getId() . DIRECTORY_SEPARATOR . $view;
        }

        return $view . '.php';
    }

    /**
     * @return string название контроллера
     */
    public function getId()
    {
        return $this->_id;
    }
}

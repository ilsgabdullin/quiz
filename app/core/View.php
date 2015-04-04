<?php
namespace app\core;

use app\core\Quiz;

/**
 * Класс для работы с представлениями.
 * @package app\core
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */
class View
{
    /**
     * @var string шаблон представления
     */
    public $layout = 'layouts/main';

    /**
     * Формирует URL.
     * @param string $route внутренний маршрут (например, 'main/index')
     * @param array $params параметры запроса
     * @return string URL с параметрами запроса
     */
    public static function url($route, $params = [])
    {
        return Quiz::app()->url($route, $params);
    }

    /**
     * Формирует вывод представления.
     * @param string $view полный путь к файлу с представлением
     * @param array $_data переменные, используемые в отображении
     */
    public function generate($view, $_data = null)
    {
        if (is_array($_data)) {
            extract($_data, EXTR_PREFIX_SAME, 'data');
        }

        ob_start();
        ob_implicit_flush(false);
        require(ROOT . 'app/views/' . $view);
        $content = ob_get_clean();

        include ROOT . 'app/views/' . $this->layout . '.php';
    }

    /**
     * Возвращает название текущего контроллер.
     * @return string название контроллера
     */
    public function getController()
    {
        return Quiz::app()->getController();
    }

    /**
     * Экранирует специальные символы.
     * @param string $text строка для экранирования
     * @return string экранированная строка
     */
    public function encode($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, 'utf-8');
    }

    /**
     * Проверяет переменную.
     * @param mixed $value переменная
     * @return mixed|null
     */
    public function check($value)
    {
        return !empty($value) ? $value : null;
    }
}

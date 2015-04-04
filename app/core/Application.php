<?php
namespace app\core;

/**
 * Базовый класс приложения.
 * @package app\core
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */

class Application
{
    /**
     * @var string контроллер по умолчанию
     */
    public $defaultController = 'Main';
    /**
     * @var string базовая директория приложения.
     * Если приложенеи устанавливается в подпапку,
     * то необходимо указать эту папку.
     */
    protected $baseURL = '/web';
    /**
     * @var string неймспейс для контроллеров приложения.
     * Используется при создании контроллера.
     */
    protected $controllersPath = 'app\\controllers\\';
    /**
     * @var string страница для вывод ошибки
     */
    protected $errorPage = 'main/error';
    /**
     * @var \PDO объект для работы с базой данных
     * Доступно из любой точки приложения через Quiz::app()->db
     */
    protected $db = null;
    /**
     * @var Config доступ к конфигурации приложегния
     */
    protected $config;
    /**
     * @var string контроллер, создаваемый при создании приложения
     */
    protected $_controller;

    /**
     * Конструктор.
     *
     * @param Config $config передается объект для работы с конфигурацией
     * @throws \Exception если не найдено запрашиваемое действие
     */
    public function __construct(Config $config)
    {
        Quiz::setApplication($this);

        $route = $this->parseRoute();
        $this->config = $config;
        $this->_controller = $route['controller'];

        $controllerName = '\\app\\controllers\\' . ucfirst($route['controller']) . 'Controller';

        $controller = new $controllerName($route['controller']);
        $action = $controller->getActionMethod($route['action']);

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            throw new \Exception('Не найдено запрашиваемое действие ' . $route['controller'] . '/' . $route['action']);
        }
    }

    /**
     * Парсит URL-запрос и возвращает название контроллера и действие.
     * @return array возвращается массив [0=>'Контроллер', 1=>'Действие']
     */
    private function parseRoute()
    {
        $controller = $this->defaultController;
        $action = false;

        $url = substr($_SERVER['REQUEST_URI'], strlen($this->baseURL));

        if ($pos = strpos($url, '?'))
            $url = substr($url, 0, $pos);

        $routes = explode('/', $url);

        if (!empty($routes[1])) {
            $controller = $routes[1];
        }

        if (!empty($routes[2])) {
            $action = $routes[2];
        }

        return ['controller' => $controller, 'action' => $action];
    }

    /**
     * Соединяется с базой данных и возвращается ссылку на созданный объект.
     * @return \PDO объект для работы с базой данных
     */
    public function db()
    {
        if ($this->db == null) {
            try {
                $this->db = new \PDO($this->config->get('db.dsn'), $this->config->get('db.username'), $this->config->get('db.password'));
                $this->db->exec("SET NAMES 'utf8';");
                $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                echo $e->getMessage();
            }
        }

        return $this->db;
    }

    /**
     * @return string название текущего контроллера
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Перенаправляет пользователя на другой URL.
     * @param string $route путь к странице вида 'контроллер/действие'
     * @param bool $terminate завершить выполнение приложения
     * @param int $statusCode HTTP-код, возвращаемый в заголовке
     */
    public function redirect($route, $terminate = true, $statusCode = 302)
    {
        header('Location: ' . $this->url($route), true, $statusCode);

        if ($terminate) {
            exit();
        }
    }

    /**
     * Формирует нормализованный URL-адрес
     * @param string $route путь к странице вида 'контроллер/действие'
     * @param array $params параметры запроса
     * @return string нормализованный URL-адрес
     */
    public function url($route, $params = [])
    {
        $url = $this->baseURL . '/';

        if (is_string($route) && !empty($route)) {
            $url .= $route;

            if (is_array($params) && count($params) > 0) {
                $_params = [];

                foreach ($params as $key => $value) {
                    $_params[] = $key . '=' . $value;
                }

                $url .= '?' . implode('&', $_params);
            }
        }

        return $url;
    }
}
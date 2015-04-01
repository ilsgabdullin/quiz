<?php
namespace app\core;

class Application
{

    protected $baseURL = '/web';
    protected $controllersPath = 'app\\controllers\\';
    protected $errorPage = 'main/error';
    protected $db = null;
    protected $config;
    protected $_controller;

    public $defaultController = 'Main';

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

    public function getController()
    {
        return $this->_controller;
    }

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

    public function redirect($route, $terminate = true, $statusCode = 302)
    {
        header('Location: ' . $this->url($route), true, $statusCode);

        if ($terminate) {
            exit();
        }
    }
}
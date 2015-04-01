<?php
namespace app\core;

use app\core\Application;

class Quiz
{
    private static $_app = null;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }

    public static function setApplication(Application $app)
    {
        if (self::$_app == null)
            self::$_app = $app;
        else
            throw new Exception('Приложение может быть создано только один раз!');
    }

    public static function run(Config $config)
    {
        if (self::$_app == null) {
            self::$_app = new Application($config);
        }
    }

    public static function app()
    {
        return self::$_app;
    }

    public static function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName = '';
        $namespace = '';

        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require_once ROOT . $fileName;
    }
}

spl_autoload_register('app\core\Quiz::autoload');
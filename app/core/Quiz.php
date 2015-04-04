<?php
namespace app\core;

use app\core\Application;

/**
 * Основный класс приложения Quiz
 * @package app\core
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */
class Quiz
{
    /**
     * @var Application хранит экземпляр приложения
     */
    private static $_app = null;

    /**
     * Конструктор.
     */
    private function __construct()
    {

    }

    /**
     * @param Application $app экземпляр приложения
     * @throws \Exception если повторно создается приложение
     */
    public static function setApplication(Application $app)
    {
        if (self::$_app == null) {
            self::$_app = $app;
        } else {
            throw new \Exception('Приложение может быть создано только один раз!');
        }
    }

    /**
     * Запуск приложения.
     * @param Config $config объект для работы с конфигурацией
     */
    public static function run(Config $config)
    {
        if (self::$_app == null) {
            self::$_app = new Application($config);
        }
    }

    /**
     * Доступ к созданному объекту приложения.
     * @return Application приложение
     */
    public static function app()
    {
        return self::$_app;
    }

    /**
     * Автозагрузщик.
     * @param string $className путь к классу
     */
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

    /**
     * Клонирование.
     */
    private function __clone()
    {

    }

    /**
     * Восстановление из строки.
     */
    private function __wakeup()
    {

    }
}

/**
 * Регистрируем автозагрузщик.
 */
spl_autoload_register('app\core\Quiz::autoload');

<?php
namespace app\core;

/**
 * Класс для работы с конфигурацией приложения.
 * @package app\core
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */
class Config
{
    /**
     * @var array данные конфигурации
     */
    private $_data = [];

    /**
     * Конструктор.
     * @param array $config с данными конфигураци
     */
    function __construct($config)
    {
        if (is_array($config)) {
            $this->_data = $config;
        }
    }

    /**
     * Возвращает конфигурационные данные по заданному пути.
     * Например, 'db.dsn' вернёт $config['db']['dsn'].
     * @param string $path путь к данным
     * @return array|string|bool данные конфигурации
     */
    public function get($path)
    {
        $value = $this->_data;

        foreach (explode('.', $path) as $token) {
            if (!empty($value[$token])) {
                $value = $value[$token];
            } else {
                $value = false;
                break;
            }
        }

        return $value;
    }
}

<?php
namespace app\core;

class Config
{

    private $_data = [];

    function __construct($config)
    {
        if (is_array($config))
            $this->_data = $config;
    }

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

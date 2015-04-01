<?php

define('ROOT', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR);

require_once ROOT . 'app/core/Quiz.php';
require_once ROOT . 'app/core/Config.php';
$config = require(ROOT . 'app/config/main.php');

\app\core\Quiz::run(new \app\core\Config($config));

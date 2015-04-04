<?php
namespace app\controllers;

use app\core\Quiz;
use app\core\Controller;
use app\models\Question;

/**
 * Контроллер MainController.
 * @package app\controllers
 */
class MainController extends Controller
{
    /**
     * Главная страница.
     */
    public function actionIndex()
    {
        $this->render('index');
    }
}
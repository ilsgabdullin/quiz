<?php
namespace app\controllers;

use app\core\Quiz;
use app\core\Controller;
use app\models\Question;

/**
 * Контроллер MainController.
 * @package app\controllers
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
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

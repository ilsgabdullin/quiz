<?php
namespace app\controllers;

use app\core\Quiz;
use app\core\Controller;
use app\models\Question;

class MainController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }
}
<?php
namespace app\controllers;

use app\core\Quiz;
use app\core\Controller;
use app\models\Poll;
use app\models\PollForm;

/**
 * Контроллер PollController
 * @package app\controllers
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */
class PollController extends Controller
{
    /**
     * @var string действие по умолчанию
     */
    public $defaultAction = 'start';

    /**
     * Отображает форму опроса.
     * Если нет активного опроса, то отображает сообщение.
     * Если пользователь ответил на вопросы, то перенаправляет на страницу с результатами опроса.
     */
    public function actionStart()
    {
        $model = Poll::getActivePoll();

        if ($model) {
            $results = new PollForm($model);

            if (!empty($_POST['Result'])) {
                $results->setData($_POST['Result']);

                if ($results->validate()) {
                    $results->save();

                    Quiz::app()->redirect('poll/results');
                }
            }

            $this->render('form', [
                'model' => $model,
                'results' => $results
            ]);
        } else {
            $this->render('empty');
        }
    }

    /**
     * Отображает результаты опроса.
     * Если нет активного опроса, то перенаправляет на страницу 'poll/start'.
     */
    public function actionResults()
    {
        $model = Poll::getActivePoll();

        if ($model) {
            $this->render('results', [
                'model' => $model
            ]);
        } else {
            Quiz::app()->redirect('poll/start');
        }
    }
}

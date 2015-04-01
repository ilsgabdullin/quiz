<?php
namespace app\controllers;

use app\core\Quiz;
use app\core\Controller;
use app\models\Poll;
use app\models\PollForm;

class PollController extends Controller
{

    public $defaultAction = 'start';

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
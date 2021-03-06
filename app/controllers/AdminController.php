<?php
namespace app\controllers;

use app\core\Quiz;
use app\core\Controller;
use app\models\Poll;
use app\models\PollForm;

/**
 * Контроллер AdminController.
 * Для администрирования опросами.
 * @package app\controllers
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */
class AdminController extends Controller
{
    /**
     * Выводит список всех опросов.
     */
    public function actionIndex()
    {
        $active = Poll::getActivePoll();
        $drafts = Poll::getDraftPolls();
        $closed = Poll::getClosedPolls();

        $this->render('index', [
            'active' => $active,
            'drafts' => $drafts,
            'closed' => $closed
        ]);
    }

    /**
     * Редактирование опроса.
     * @throws \Exception
     */
    public function actionUpdate()
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;
        $model = Poll::model()->findByPk($id);

        if ($model) {
            $data = $model->getFormData();

            if (!empty($_POST['Poll'])) {
                $model->populate($_POST['Poll']);
                $data = $_POST['Poll'];

                if ($model->validate()) {
                    $model->savePoll();

                    Quiz::app()->redirect('admin/index');
                }
            }

            $this->render('form', [
                'model' => $model,
                'data' => $data
            ]);
        } else {
            Quiz::app()->redirect('admin/index');
        }
    }

    /**
     * Создание опроса.
     */
    public function actionCreate()
    {
        $model = new Poll;

        $data = [];

        if (!empty($_POST['Poll'])) {
            $model->populate($_POST['Poll']);
            $data = $_POST['Poll'];

            if ($model->validate()) {
                $model->savePoll();

                Quiz::app()->redirect('admin/index');
            }
        }

        $this->render('form', [
            'model' => $model,
            'data' => $data
        ]);
    }

    /**
     * Удаление опроса.
     * @throws \Exception
     */
    public function actionDelete()
    {
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
        $model = Poll::model()->findByPk($id);

        $model->delete();

        Quiz::app()->redirect('admin/index');
    }

    /**
     * Переводит опрос в список закрытых.
     * @throws \Exception
     */
    public function actionClose()
    {
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
        $model = Poll::model()->findByPk($id);

        $model->state = Poll::POLL_STATE_CLOSED;
        $model->save();

        Quiz::app()->redirect('admin/index');
    }

    /**
     * Переводит опрос в список активных.
     * @throws \Exception
     */
    public function actionActivate()
    {
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
        $model = Poll::model()->findByPk($id);

        Quiz::app()->db()->exec('UPDATE `poll` SET `state` = ' . Poll::POLL_STATE_CLOSED . ' WHERE `state` = ' . Poll::POLL_STATE_ACTIVE);

        $model->state = Poll::POLL_STATE_ACTIVE;
        $model->save();

        Quiz::app()->redirect('admin/index');
    }

    /**
     * Просмотр опроса.
     * @throws \Exception
     */
    public function actionView()
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : 0;
        $model = Poll::model()->findByPk($id);
        $results = new PollForm($model);

        if (!empty($_POST['Filter'])) {
            $results->setData($_POST['Filter']);
            $query = $results->getQuery();
            $explanation = $results->getQueryExplanation();

            $this->render('results', [
                'model' => $model,
                'query' => $query,
                'explanation' => $explanation
            ]);
        } else {
            $this->render('filter', [
                'model' => $model
            ]);
        }
    }
}

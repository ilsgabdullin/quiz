<?php
namespace app\models;

use app\core\Model;
use app\models\Question;
use app\models\Answer;

class Poll extends Model
{
    const POLL_STATE_ACTIVE = 1;
    const POLL_STATE_DRAFT = 2;
    const POLL_STATE_CLOSED = 3;

    private $_questions = [];
    private $_formData = [];

    protected $tableName = 'poll';
    protected $attributes = ['title', 'state'];

    public function getQuestions($reload = false)
    {
        if (count($this->_questions) == 0 || $reload)
            $this->_questions = Question::model()->findByAttributes(['poll_id' => $this->id]);

        return $this->_questions;
    }

    public static function getActivePoll()
    {
        $model = Poll::model()->findByAttributes(['state' => Poll::POLL_STATE_ACTIVE]);

        if (!empty($model[0]) && ($model[0] instanceof Poll)) {
            return $model[0];
        } else {
            return false;
        }
    }

    public static function getDraftPolls()
    {
        $models = Poll::model()->findByAttributes(['state' => Poll::POLL_STATE_DRAFT]);

        return $models;
    }

    public static function getClosedPolls()
    {
        $models = Poll::model()->findByAttributes(['state' => Poll::POLL_STATE_CLOSED]);

        return $models;
    }

    public function getFormData()
    {
        $data = [];
        $data['title'] = $this->title;
        $data['question'] = [];

        $questions = $this->getQuestions();

        foreach ($questions as $question) {
            $item = [];
            $item['id'] = $question->id;
            $item['title'] = $question->title;

            if ($question->required)
                $item['required'] = 'on';

            if ($question->type == Question::QUESTION_MULTIPLE)
                $item['type'] = 'on';

            $answers = $question->getAnswers();

            $item['answer'] = [];

            foreach ($answers as $answer) {
                $item['answer'][] = ['id' => $answer->id, 'title' => $answer->title];
            }

            $data['question'][] = $item;
        }

        return $data;
    }

    public function populate($data)
    {
        $this->_formData = $data;
    }

    public function validate()
    {
        $data = $this->_formData;

        if (empty($data['title'])) {
            $this->addError('Введите название опроса.');
        }

        if (!empty($data['title']) && strlen($data['title']) > 250) {
            $this->addError('Слишком длинное название опроса.');
        }

        $questionsCount = 0;
        $requiredQuestion = false;

        if (!empty($data['question']) && count($data['question']) > 0) {
            foreach ($data['question'] as $question) {
                $answersCount = 0;

                if (!empty($question['required'])) {
                    $requiredQuestion = true;
                }

                if (empty($question['title'])) {
                    $this->addError('Введите вопрос.');
                }

                if (strlen($question['title']) > 250) {
                    $this->addError('Слишком длинный вопрос.');
                }


                if (!empty($question['answer']) && count($question['answer']) > 0) {
                    foreach ($question['answer'] as $answer) {
                        if (!empty($answer['title'])) {
                            if (strlen($answer['title']) > 250) {
                                $this->addError('Слишком длинный ответ.');
                            }

                            $answersCount++;
                        }
                    }
                }

                if ($answersCount < 2) {
                    $this->addError('Вопрос должен содержать не менее 2х ответов.');
                }

                $questionsCount++;
            }
        }

        if (!$requiredQuestion) {
            $this->addError('Хотя бы один вопрос должен быть обязательным.');
        }

        if ($questionsCount == 0) {
            $this->addError('Опрос должен содержать хотя бы один вопрос.');
        }

        return !$this->hasErrors();
    }

    public function savePoll()
    {
        $data = $this->_formData;

        $questionIDs = [];

        $this->title = $data['title'];
        $this->save();

        foreach ($data['question'] as $question) {
            if (!empty($question['id']) && (int)$question['id'] > 0) {
                $questionModel = Question::model()->findByPk((int)$question['id']);
                $questionIDs[] = (int)$question['id'];
            } else {
                $questionModel = new Question;
            }
            $questionModel->poll_id = $this->id;
            $questionModel->title = $question['title'];
            $questionModel->required = !empty($question['required']) ? 1 : 0;
            $questionModel->type = !empty($question['type']) ? 2 : 1;
            $questionModel->save();

            foreach ($question['answer'] as $answer) {
                if (!empty($answer['id']) && (int)$answer['id'] > 0) {
                    $answerModel = Answer::model()->findByPk((int)$answer['id']);
                } else {
                    $answerModel = new Answer;
                }
                $answerModel->question_id = $questionModel->id;
                $answerModel->title = $answer['title'];
                $answerModel->save();
            }
        }

        if (count($questionIDs) > 0) {
            $sql = 'SELECT GROUP_CONCAT(DISTINCT `id` SEPARATOR ",") as `to_delete` FROM `question` WHERE `poll_id` = ' . $this->id . ' AND `id` NOT IN (' . implode(',', $questionIDs) . ')';
            $questionIDs = $this->db->query($sql, \PDO::FETCH_ASSOC)->fetch();

            if (!empty($questionIDs['to_delete'])) {
                $this->db->exec('DELETE FROM `question` WHERE `id` IN (' . $questionIDs['to_delete'] . ')');

                $sql = 'SELECT GROUP_CONCAT(DISTINCT `id` SEPARATOR ",") as `to_delete` FROM `answer` WHERE `question_id` IN (' . $questionIDs['to_delete'] . ')';
                $IDs = $this->db->query($sql, \PDO::FETCH_ASSOC)->fetch();

                if (!empty($IDs['to_delete'])) {
                    $this->db->exec('DELETE FROM `answer` WHERE `id` IN (' . $IDs['to_delete'] . ')');
                    $this->db->exec('DELETE FROM `result_answer` WHERE `answer_id` IN (' . $IDs['to_delete'] . ')');
                }
            }
        }

        return true;
    }

    public function delete()
    {
        $this->db->exec('DELETE `r`, `ra` FROM `result` `r` LEFT JOIN `result_answer` `ra` ON `ra`.`result_id`=`r`.`id` WHERE `r`.`poll_id` = ' . $this->id);
        $this->db->exec('DELETE `q`, `a` FROM `question` `q` LEFT JOIN `answer` `a` ON `a`.`question_id`=`q`.`id` WHERE `q`.`poll_id` = ' . $this->id);
        $this->db->exec('DELETE FROM `poll` WHERE `id` = ' . $this->id);
    }
}

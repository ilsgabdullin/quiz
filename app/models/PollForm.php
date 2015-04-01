<?php
namespace app\models;

use app\core\Quiz;
use app\models\Poll;
use app\models\Question;
use app\models\Result;

class PollForm
{
    private $_poll = null;
    private $_data = [];

    public function __construct(Poll $poll)
    {
        $this->_poll = $poll;
    }

    public function get($key)
    {
        if (!empty($this->_data['question-' . $key])) {
            return $this->_data['question-' . $key];
        } else {
            return [];
        }
    }

    public function set($key, $value)
    {
        $this->_data['question-' . $key] = $value;
    }

    public function setData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (preg_match('/^question/', $key)) {
                    $this->_data[$key] = $value;
                }
            }
        }
    }

    public function getData()
    {
        return $this->_data;
    }

    public function save()
    {
        $result = false;

        $model = new Result;
        $model->poll_id = $this->_poll->id;

        if ($model->save()) {

            foreach ($this->_data as $data) {
                if (is_array($data)) {
                    foreach ($data as $item) {
                        $model->saveAnswer($item);
                    }
                } else {
                    $model->saveAnswer($data);
                }
            }
        }

        return $result;
    }

    public function validate()
    {
        $validate = true;

        foreach ($this->_poll->getQuestions() as $question) {
            $data = $this->get($question->id);

            if (is_array($data)) {
                $data = array_intersect(array_keys($data), $question->getAnswersID());
            } elseif (!in_array($data, $question->getAnswersID())) {
                $data = null;
            }

            $this->set($question->id, $data);

            if ($question->required && !$data) {
                $this->_poll->addError('Необходимо ответить на вопросы, отмеченные звездочкой (*)!');
                $validate = false;
            }

            if ($question->type == Question::QUESTION_SINGLE && is_array($data) && count($data) > 0) {
                $this->_poll->addError('Вопрос преполагает только один варианта ответа.');
                $validate = false;
            }
        }

        return $validate;
    }

    public function getQuery()
    {
        $query = '';

        if (!empty($this->_data) && count($this->_data) > 0) {
            $params = [];

            foreach ($this->_data as $value) {
                $params[] = '(`answer_id` = ' . implode(' OR `answer_id` = ', array_keys($value)) . ')';
            }

            $query = ' WHERE ' . implode(' AND ', $params);
        }

        return $query;
    }

    public function getQueryExplanation()
    {
        $explanation = [];

        if (!empty($this->_data) && count($this->_data) > 0) {
            $ids = [];

            foreach ($this->_data as $key => $value) {
                $ids[] = implode(',', array_keys($value));
            }


            $sql = 'SELECT `a`.`id`, `a`.`question_id`, `q`.`title` as `question`, `a`.`title` as `answer` FROM `answer` `a` LEFT JOIN `question` `q` ON `a`.`question_id` = `q`.`id` WHERE `a`.`id` IN (' . implode(',', $ids) . ')';
            $queries = Quiz::app()->db()->query($sql, \PDO::FETCH_ASSOC)->fetchAll();

            if (!empty($queries) && count($queries) > 0) {
                foreach ($queries as $data) {
                    $explanation[$data['question']][] = '<strong>' . $data['answer'] . '</strong>';
                }

                foreach ($explanation as $key => $value) {
                    $explanation[] = $key . ' ' . implode(' ИЛИ ', $value);
                    unset($explanation[$key]);
                }
            }
        }

        return implode('. ', $explanation);
    }
}

<?php
namespace app\models;

use app\core\Model;
use app\models\Answer;

class Question extends Model
{
    const QUESTION_SINGLE = 1;
    const QUESTION_MULTIPLE = 2;

    private $_answers = [];

    protected $tableName = 'question';
    protected $attributes = ['title', 'type', 'required'];

    public function getAnswersID()
    {
        $ids = [];

        foreach ($this->getAnswers() as $answer)
            $ids[] = $answer->id;

        return $ids;
    }

    public function getAnswers($reload = false)
    {
        if (count($this->_answers) == 0 || $reload)
            $this->_answers = Answer::model()->findByAttributes(['question_id' => $this->id]);

        return $this->_answers;
    }

    public function getAnswersWithResults($query = false)
    {
        $result = [];
        $params = ['question_id' => $this->id];

        if ($query) {
            $sql = 'SELECT `a`.`title`, `result`.`count`, (SELECT COUNT(`result_id`) FROM `result_answer`' . $query . ') as `total` FROM `answer` `a` LEFT JOIN (SELECT `ra`.`answer_id`, COUNT(`ra`.`answer_id`) 	as `count` FROM `result_answer` `ra` INNER JOIN (SELECT DISTINCT `result_id` FROM `result_answer`' . $query . ') `t` ON `t`.`result_id`=`ra`.`result_id` GROUP BY `ra`.`answer_id`) `result` ON `result`.`answer_id`=`a`.`id` WHERE `a`.`question_id`=:question_id';
        } else {
            $sql = 'SELECT a.`title`, COUNT(`ra`.`result_id`) as `count`, (SELECT COUNT(`id`) FROM `result` WHERE `poll_id`=:poll_id) as `total` FROM `answer` `a` LEFT JOIN `result_answer` `ra` ON `ra`.`answer_id`=`a`.`id` WHERE `question_id`=:question_id GROUP BY `a`.`id`';
            $params['poll_id'] = $this->poll_id;
        }

        $statement = $this->db->prepare($sql);

        if ($statement->execute($params)) {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $result;
    }

    public function validate()
    {
        return true;
    }
}

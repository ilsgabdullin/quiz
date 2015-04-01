<?php
namespace app\models;

use app\core\Model;
use app\models\ResultAnswer;

class Result extends Model
{
    protected $tableName = 'result';

    public function saveAnswer($value)
    {
        $answer = new ResultAnswer;
        $answer->result_id = $this->id;
        $answer->answer_id = $value;

        return $answer->save();
    }

    public function validate()
    {
        return true;
    }
}

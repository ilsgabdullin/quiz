<?php
namespace app\models;

use app\core\Model;

class ResultAnswer extends Model
{
    protected $tableName = 'result_answer';

    public function validate()
    {
        return true;
    }
}

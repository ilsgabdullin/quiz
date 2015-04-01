<?php
namespace app\models;

use app\core\Model;

class Answer extends Model
{
    protected $tableName = 'answer';
    protected $attributes = ['title'];

    public function validate()
    {
        return true;
    }
}

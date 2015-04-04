<?php
namespace app\models;

use app\core\Model;

/**
 * Модель для работы с таблицей 'answer'
 * @package app\models
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */
class Answer extends Model
{
    /**
     * @var string название таблицы в базе данных
     */
    protected $tableName = 'answer';

    /**
     * @return bool|mixed
     */
    public function validate()
    {
        return true;
    }
}

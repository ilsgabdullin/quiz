<?php
namespace app\core;

abstract class Model
{
    protected $tableName = null;
    protected $primaryKey = null;
    protected $db;
    protected $attributes = [];
    protected $isNewRecord = true;

    private $_data = null;
    private $_errors = [];

    public function __construct()
    {
        if (Quiz::app()->db() instanceof \PDO) {
            $this->db = Quiz::app()->db();

            if ($this->tableName != null) {
                $data = $this->db->query('SHOW KEYS FROM `poll` WHERE Key_name = "PRIMARY"', \PDO::FETCH_ASSOC)->fetch();

                if (!empty($data['Column_name'])) {
                    $this->primaryKey = $data['Column_name'];
                }
            } else {
                throw new \Exception('Не задано название таблицы для модели!');
            }
        } else {
            throw new \Exception('Не задан компонент для работы с базой данных.');
        }
    }

    public function isNewRecord()
    {
        return $this->isNewRecord;
    }

    public function addError($message)
    {
        if (is_string($message))
            $this->_errors[] = $message;
    }

    public function getErrors()
    {
        return implode('<br>', array_unique($this->_errors));
    }

    public function hasErrors()
    {
        return count($this->_errors) > 0;
    }

    protected function setData($data = [])
    {
        if (is_array($data)) {
            $this->_data = $data;
            $this->isNewRecord = false;
        }
    }

    public static function model()
    {
        return new static();
    }

    public function __get($property)
    {
        if (!empty($this->_data[$property])) {
            return $this->_data[$property];
        }
    }

    public function __set($property, $value)
    {
        $this->_data[$property] = (string)$value;
    }

    protected function getColumns()
    {
        return array_keys($this->_data);
    }

    public function save()
    {
        $result = false;

        if ($this->isNewRecord) {
            $sql = 'INSERT INTO `' . $this->tableName . '` (`' . implode('`, `', $this->getColumns()) . '`) VALUES (:' . implode(', :', $this->getColumns()) . ')';
        } else {
            $columns = $this->getColumns();

            foreach ($columns as $key => $value) {
                $columns[$key] = '`' . $value . '`=:' . $value;
            }
            $sql = 'UPDATE `' . $this->tableName . '`  SET ' . implode(', ', $columns) . ' WHERE `' . $this->primaryKey . '` = ' . (int)$this->{$this->primaryKey};
        }

        try {
            $result = $this->db->prepare($sql)->execute($this->_data);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        if ($this->isNewRecord && $this->primaryKey) {
            $this->{$this->primaryKey} = $this->db->lastInsertId();
        }

        $this->isNewRecord = false;

        return $result;
    }

    public function findByPk($id)
    {
        if ($this->primaryKey != null) {
            $sql = 'SELECT * FROM `' . $this->tableName . '` WHERE `' . $this->primaryKey . '` = ' . (int)$id;
            $this->_data = $this->db->query($sql, \PDO::FETCH_ASSOC)->fetch();

            if (!$this->_data) {
                throw new \Exception('Запись не найдена.');
            } else {
                $this->isNewRecord = false;
                return $this;
            }
        } else {
            throw new \Exception('У таблицы не задан первичный ключ.');
        }
    }

    public function findByAttributes($attributes)
    {
        if (is_array($attributes)) {

            $params = [];
            $result = [];

            foreach ($attributes as $key => $value) {
                $params[] = '`' . $key . '`' . '=:' . $key;
            }

            $sql = 'SELECT * FROM `' . $this->tableName . '` WHERE ' . implode(',', $params);
            $statement = $this->db->prepare($sql);

            if ($statement->execute($attributes)) {
                while ($data = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $model = new static();
                    $model->setData($data);
                    $result[] = $model;
                }
            }

            return $result;
        }
    }

    public abstract function validate();
}
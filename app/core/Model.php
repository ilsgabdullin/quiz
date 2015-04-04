<?php
namespace app\core;

/**
 * Модель для работы с базой данных
 * @package app\core
 * @author Ильсур Габдуллин <ilsgabdullin@gmail.com>
 */

abstract class Model
{
    /**
     * @var string название таблицы
     */
    protected $tableName = null;
    /**
     * @var string название столбца с первичным ключем
     */
    protected $primaryKey = null;
    /**
     * @var \PDO ссылка на объект для обращения к базе данных
     */
    protected $db;
    /**
     * @var array поля таблицы
     */
    protected $attributes = [];
    /**
     * @var bool новая запись или нет
     */
    protected $isNewRecord = true;
    /**
     * @var array массив с данными модели
     */
    private $_data = null;
    /**
     * @var array ошибки при валидации
     */
    private $_errors = [];

    /**
     * Конструктор.
     * @throws \Exception
     */
    public function __construct()
    {
        if (Quiz::app()->db() instanceof \PDO) {
            $this->db = Quiz::app()->db();

            if ($this->tableName != null) {
                $data = $this->db->query('SHOW KEYS FROM `poll` WHERE Key_name = "PRIMARY"',
                    \PDO::FETCH_ASSOC)->fetch();

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

    /**
     * @return static новый экземпляр класса
     */
    public static function model()
    {
        return new static();
    }

    /**
     * @return bool новая запись или нет
     */
    public function isNewRecord()
    {
        return $this->isNewRecord;
    }

    /**
     * Добавляет ошибку
     * @param string $message строка с ошабкой
     */
    public function addError($message)
    {
        if (is_string($message)) {
            $this->_errors[] = $message;
        }
    }

    /**
     * @return string строка с ошибками
     */
    public function getErrors()
    {
        return implode('<br>', array_unique($this->_errors));
    }

    /**
     * @return bool есть ли ошибки при валидации
     */
    public function hasErrors()
    {
        return count($this->_errors) > 0;
    }

    /**
     * Возвращает значение поля по заданному названию поля.
     * @param string $property название поля
     * @return string значение поля
     */
    public function __get($property)
    {
        if (!empty($this->_data[$property])) {
            return $this->_data[$property];
        } else {
            return null;
        }
    }

    /**
     * Устанавливает значени поля.
     * @param string $property название поля
     * @param string $value значение поля
     */
    public function __set($property, $value)
    {
        $this->_data[$property] = (string)$value;
    }

    /**
     * Сохраняет модель в базе данных.
     * @return bool модель успешно сохранена
     * @throws \Exception если произошла ошибка при работе с БД
     */
    public function save()
    {
        if ($this->isNewRecord) {
            $sql = 'INSERT INTO `' . $this->tableName . '` (`' . implode('`, `',
                    $this->getColumns()) . '`) VALUES (' . implode(', :', $this->getColumns()) . ')';
        } else {
            $columns = $this->getColumns();

            foreach ($columns as $key => $value) {
                $columns[$key] = '`' . $value . '`=:' . $value;
            }
            $sql = 'UPDATE `' . $this->tableName . '`  SET ' . implode(', ',
                    $columns) . ' WHERE `' . $this->primaryKey . '` = ' . (int)$this->{$this->primaryKey};
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

    protected function getColumns()
    {
        return array_keys($this->_data);
    }

    /**
     * Находит запись по первичному ключу и возвращает модель с данными.
     * @param integer $id
     * @throws \Exception
     */
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

    /**
     * Находит запись по аттрибутам и возвращает массив с моделями.
     * @param array $attributes массив с парой название столбца => значение
     * @return array список с найденными моделями (Model)
     */
    public function findByAttributes($attributes)
    {
        if (is_array($attributes)) {

            $params = [];
            $result = [];

            foreach ($attributes as $key => $value) {
                $params[] = '`' . $key . '`' . '=:' . $key;
            }

            $sql = 'SELECT * FROM `' . $this->tableName . '` WHERE ' . implode(' AND ', $params);
            $statement = $this->db->prepare($sql);

            if ($statement->execute($attributes)) {
                while ($data = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $model = new static();
                    $model->setData($data);
                    $result[] = $model;
                }
            }

            return $result;
        } else {
            return false;
        }
    }

    /**
     * Устанавливает данные модели.
     * @param array $data массив с данными (название поля => значение)
     */
    protected function setData($data = [])
    {
        if (is_array($data)) {
            $this->_data = $data;
            $this->isNewRecord = false;
        }
    }

    /**
     * @return mixed
     */
    public abstract function validate();
}

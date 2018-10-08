<?php
namespace app\components;


abstract class ModelRecord
{
    protected $_errors = [];

    public function __construct($data=[])
    {
        if ($data) {
            Application::configure($this, $data);
        }
    }

    public function setErrors($errors) {
        $this->_errors = $errors;
    }

    public function addError($error) {
        $this->_errors[] = $error;
    }

    public function getErrors() {
        return $this->_errors;
    }

    public function hasErrors() {
        return !empty($this->_errors);
    }
    public function validate() {
        return true;
    }

    /**
     * @return \PDO
     */
    public static function getDb() {
        return Application::instance()->db;
    }

    /**
     * @throws \Exception
     */
    public static function tableName() {
        throw new \Exception('Model must return table name in `tableName()` method');
    }

    public static function getPrimaryKeyAttribute() {
        return 'id';
    }

    public static function getAll($from = null, $limit = null)
    {
        $query = 'SELECT * FROM ' . static::tableName();
        $params = [];
        if ($from !== null && $limit !== null) {
            $query .= ' LIMIT :from, :limit';
            $params[':from'] = $from;
            $params[':limit'] = $limit;

        }
        $stmt = static::getDb()->prepare($query);
        if ($params) {
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value, \PDO::PARAM_INT);
            }
        }
        $stmt->execute();
        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $model = new static();
            Application::configure($model, $row);
            $data[] = $model;
        }
        return $data;
    }

    public function exists() {
        $pkAttribute = static::getPrimaryKeyAttribute();
        return static::getById($this->$pkAttribute) !== null;
    }

    public static function getByIdQuery() {
        return "SELECT
                *
            FROM
                " . static::tableName() . " t
            WHERE
                t.id = ?
            LIMIT
                0,1";
    }

    public static function getById($id)
    {
        $query = static::getByIdQuery();

        $stmt = static::getDb()->prepare( $query );
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $model = new static();
        Application::configure($model, $row);
        return $model;
    }

    public function afterCreate() {
    }

    public function afterUpdate() {
    }

    public function afterValidate() {
        return true;
    }

    public function create()
    {
        if (!$this->validate()) {
            return false;
        }
        if (!$this->afterValidate()) {
            return false;
        }
        $queryBits = [];
        foreach (get_object_vars($this->getSchema())  as $attribute => $value) {
            $queryBits[] = $attribute . '=:' . $attribute;
        }
        $query = 'INSERT INTO ' . static::tableName() . ' SET '. implode(', ', $queryBits);
        $stmt = static::getDb()->prepare($query);
        foreach (get_object_vars($this->getSchema())  as $attribute => $value) {
            $stmt->bindParam(":$attribute", $this->$attribute);
        }
        if($stmt->execute()){
            $pkAttribute = static::getPrimaryKeyAttribute();
            $this->{$pkAttribute} = static::getDb()->lastInsertId();
            $this->afterCreate();
            return true;
        } else {
            $this->setErrors($stmt->errorInfo());
        }

        return false;
    }

    public function update()
    {
        if (!$this->validate()) {
            return false;
        }
        if (!$this->afterValidate()) {
            return false;
        }
        $pkAttribute = static::getPrimaryKeyAttribute();
        $queryBits = [];
        foreach (get_object_vars($this->getSchema())  as $attribute => $value) {
            if ($pkAttribute != $attribute) {
                $queryBits[] = $attribute . '=:' . $attribute;
            }
        }
        $query = 'UPDATE ' . static::tableName() . ' SET '. implode(', ', $queryBits) .' WHERE '.$pkAttribute.'=:'.$pkAttribute;
        $stmt = static::getDb()->prepare($query);
        $stmt->bindParam(":$pkAttribute", $this->$pkAttribute);
        foreach (get_object_vars($this->getSchema())  as $attribute => $value) {
            if ($pkAttribute != $attribute) {
                $stmt->bindParam(":$attribute", $this->$attribute);
            }
        }
        if($stmt->execute()){
            $this->afterUpdate();
            return true;
        } else {
            $this->setErrors($stmt->errorInfo());
        }

        return false;
    }

    public function delete()
    {
        $pkAttribute = static::getPrimaryKeyAttribute();
        $query = 'DELETE FROM ' . static::tableName() . ' WHERE '.$pkAttribute.'=:'.$pkAttribute;
        $stmt = static::getDb()->prepare($query);
        $stmt->bindParam(":$pkAttribute", $this->$pkAttribute);
        if($stmt->execute()){
            return true;
        } else {
            $this->setErrors($stmt->errorInfo());
        }

        return false;
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else if (method_exists($this, 'set'.ucfirst($name))) {
            $this->{'set'.ucfirst($name)}($value);
        } else {
            throw new \Exception('Setting unknown property');
        }
    }
}
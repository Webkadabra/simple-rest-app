<?php
namespace app\models;

use app\components\ModelRecord;

class Author extends ModelRecord
{
    public $name, $id;

    public function getSchema() {
        return new \app\structures\Author();
    }

    public static function tableName() {
        return 'author';
    }

    public function validate() {
        if (!$this->name) {
            $this->addError('Author must have a name!');
        }

        return $this->_errors ? false : true;
    }

    public function create()
    {
        if (!$this->validate()) {
            return false;
        }
        $query = "INSERT INTO
                " . static::tableName() . "
            SET
                name=:name";

        $stmt = static::getDb()->prepare($query);

        $stmt->bindParam(":name", $this->name);

        if($stmt->execute()){
            $pkAttribute = static::getPrimaryKeyAttribute();
            $this->{$pkAttribute} = static::getDb()->lastInsertId();
            return true;
        } else {
            $this->setErrors($stmt->errorInfo());
        }

        return false;
    }
}
<?php
namespace app\models;

use app\components\Application;
use app\components\ModelRecord;

class Book extends ModelRecord  implements \JsonSerializable
{
    public $id, $title, $description, $pub_date;

    public function getSchema() {
        return new \app\structures\Book();
    }

    /**
     * @var Author[]
     */
    protected $_authors = null;

    public static function tableName() {
        return 'book';
    }

    public function setAuthors($data) {
        foreach ($data as $row) {
            $model = new Author($row);
            $this->_authors[] = $model;
        }
    }

    public function getAuthors() {
        if ($this->_authors === null) {
            $this->loadAuthors();
        }
        return $this->_authors;
    }

    public function validate() {
        if (!$this->title) {
            $this->addError('Book must have a name!');
        }
        if (!$this->getAuthors()) {
            $this->addError('Book must have at least one author!');
        }
        return $this->_errors ? false : true;
    }

    public function afterValidate() {
        foreach ($this->_authors as $author) {
            if (!$author->id) {
                if (!$author->validate()) {
                    $this->addError('Author is not set correctly: ' . implode("\n", $author->getErrors()));
                }
                $author->create();
            } else if (!$author->exists()) {
                $this->addError('Author ' . $author->id .' not found');
            }
        }
        return true;
    }

    /**
     * save `authors` relation
     * @return bool|void
     */
    public function afterCreate()
    {
        $this->saveAuthors();
    }

    /**
     * save `authors` relation
     * @return bool|void
     */
    public function afterUpdate()
    {
        $this->saveAuthors();
        $this->loadAuthors();
    }

    protected function saveAuthors() {
        $stmt = static::getDb()->prepare( 'delete from `book_author` where book_id = ?' );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        foreach ($this->_authors as $author) {
            $query = 'INSERT INTO `book_author` SET book_id=:book_id, author_id=:author_id';
            $stmt = static::getDb()->prepare($query);
            $stmt->bindParam(":book_id", $this->id);
            $stmt->bindParam(":author_id", $author->id);
            $stmt->execute();
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize () {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'pub_date' => $this->pub_date,
            'authors' => $this->getAuthors(),
        ];
    }

    /**
     * load book authors
     */
    public function loadAuthors() {
        $query = 'SELECT a.* FROM author a JOIN book_author ba ON ba.author_id = a.id
              WHERE ba.book_id = ?';
        $stmt = static::getDb()->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $data = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $model = new Author();
            Application::configure($model, $row);
            $data[] = $model;
        }
        $this->_authors = $data;
    }
}
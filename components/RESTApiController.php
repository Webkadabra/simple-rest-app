<?php
namespace app\components;


use app\exceptions\BadRequest;
use app\exceptions\NotFound;

class RESTApiController
{
    const MODEL_CLASS = null;

    public function getIdParam() {
        return 'id';
    }

    /**
     * To exit application or disallow access, throw an Exception or `exit` etc.
     */
    public function checkAccess() {
    }

    public function actionList() {
        $from = intval($_GET['from']) ?? null;
        $limit = intval($_GET['limit']) ?? null;
        return (static::MODEL_CLASS)::getAll($from, $limit);
    }

    /**
     * @return mixed
     * @throws NotFound
     */
    public function actionRead()
    {
        $id = intval($_GET[$this->getIdParam()]) ?? null;
        if (!$id) {
            throw new NotFound();
        }
        if (!$model = (static::MODEL_CLASS)::getById(intval($_GET[$this->getIdParam()]))) {
            throw new NotFound();
        }
        return $model;
    }

    /**
     * @return mixed
     * @throws BadRequest
     * @throws NotFound
     */
    public function actionDelete()
    {
        if($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            throw new BadRequest();
        }
        $id = intval($_GET[$this->getIdParam()]) ?? null;
        if (!$id) {
            throw new NotFound();
        }
        if (!$model = (static::MODEL_CLASS)::getById(intval($_GET[$this->getIdParam()]))) {
            throw new NotFound();
        }
        if ($model->delete()) {
            return ['success' => true, 'message' => 'Item deleted'];
        } else {
            return ['error' => $model->getErrors()];
        }
    }
}
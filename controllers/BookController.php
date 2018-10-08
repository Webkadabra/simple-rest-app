<?php
namespace app\controllers;

use app\components\Application;
use app\components\RESTApiController;
use app\exceptions\NotFound;

class BookController extends RESTApiController
{
    const MODEL_CLASS = 'app\models\Book';

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
        if (!$model = \app\models\Book::getById(intval($_GET[$this->getIdParam()]))) {
            throw new NotFound();
        }
        return $model;
    }

    public function actionCreate()
    {
        $modelClass = static::MODEL_CLASS;
        /** @var \app\components\ModelRecord $model */
        $model = new $modelClass;

        $data = json_decode(file_get_contents("php://input"));
        Application::configure($model, $data);

        if ($model->create()) {
            return $model;
        } else {
            return ['error' => $model->getErrors()];
        }
    }

    /**
     * @throws NotFound
     */
    public function actionUpdate()
    {
        $id = intval($_GET[$this->getIdParam()]) ?? null;
        if (!$id) {
            throw new NotFound();
        }
        if (!$model = \app\models\Book::getById(intval($_GET[$this->getIdParam()]))) {
            throw new NotFound();
        }
        $data = json_decode(file_get_contents("php://input"), true);
        unset($data['id']); // do not allow overriding ID to prevent duplicate primary key errors
        Application::configure($model, $data);
        if ($model->update()) {
            return $model;
        } else {
            return ['error' => $model->getErrors()];
        }
    }
}
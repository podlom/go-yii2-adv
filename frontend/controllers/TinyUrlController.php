<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\TinyUrl;
use common\models\TinyUrlSearch;

class TinyUrlController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create'],
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'roles' => ['@'], // тільки для авторизованих користувачів
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new TinyUrl();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->user_id = Yii::$app->user->id;
            $model->status = 1; // або встановіть як потрібно

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Tiny URL додано успішно!');
                return $this->redirect(['create']); // або redirect на іншу сторінку
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $searchModel  = new TinyUrlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // DEBUG ONCE: confirm the actual SQL has the LEFT JOIN on tiny_url_id
        Yii::debug($dataProvider->query->createCommand()->rawSql, __METHOD__);

        $model = new TinyUrl();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_at = time();
            $model->updated_at = time();
            $model->user_id = Yii::$app->user->id;
            $model->status = 1;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Новий запис успішно додано!');
                return $this->refresh(); // оновити сторінку
            }
        }

        return $this->render('index', [
            'model' => $model,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider, // <-- this exact var passes to GridView
        ]);
    }

}


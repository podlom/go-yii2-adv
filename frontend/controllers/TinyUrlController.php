<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\TinyUrl;

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
        $query = \common\models\TinyUrl::find()
            ->where(['user_id' => Yii::$app->user->id]) // тільки свої URL
            ->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $model = new \common\models\TinyUrl();

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
            'dataProvider' => $dataProvider,
        ]);
    }

}


<?php

namespace frontend\controllers;

use frontend\models\UrlRedirectLogSearch;
use yii\web\Controller;
use yii\filters\AccessControl;

class UrlStatisticsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UrlRedirectLogSearch();
        $dataProvider = $searchModel->search();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}

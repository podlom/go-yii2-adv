<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

class UrlRedirectLogSearch extends Model
{
    public function search(): ArrayDataProvider
    {
        $rows = Yii::$app->db->createCommand("
            SELECT url, COUNT(*) as cnt_url
            FROM url_redirect_log
            GROUP BY url
            ORDER BY cnt_url DESC
        ")->queryAll();

        return new ArrayDataProvider([
            'allModels' => $rows,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'attributes' => ['url', 'cnt_url'],
                'defaultOrder' => ['cnt_url' => SORT_DESC],
            ],
        ]);
    }
}

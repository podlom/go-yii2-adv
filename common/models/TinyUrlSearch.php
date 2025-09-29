<?php

declare(strict_types=1);

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class TinyUrlSearch extends TinyUrl
{
    public $clicks_count;

    public function rules(): array
    {
        return [
            [['id', 'user_id', 'clicks_count'], 'integer'],
            [['key', 'url', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = TinyUrl::find()
            ->alias('tu')
            ->withClicksCount();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'created_at',
                    'updated_at',
                    // expose sorting for clicks_count
                    'clicks_count' => [
                        'asc' => ['clicks_count' => SORT_ASC],
                        'desc' => ['clicks_count' => SORT_DESC],
                        'label' => 'Clicks',
                    ],
                ],
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // Apply filters WITHOUT calling $query->select(...)
        $query->andFilterWhere(['tu.user_id' => $this->user_id]);
        $query->andFilterWhere(['tu.id' => $this->id]);
        $query->andFilterWhere(['like', 'tu.key', $this->key]);
        $query->andFilterWhere(['like', 'tu.url', $this->url]);

        // Filtering on computed column uses HAVING:
        if ($this->clicks_count !== null && $this->clicks_count !== '') {
            $query->andHaving(['clicks_count' => (int)$this->clicks_count]);
        }

        return $dataProvider;
    }
}
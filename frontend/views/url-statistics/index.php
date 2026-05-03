<?php

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Статистика переходів по URL';

?>

<div class="url-statistics-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'url',
                'format' => 'url',
                'label' => 'URL',
            ],
            [
                'attribute' => 'cnt_url',
                'label' => 'Кількість переходів',
            ],
        ],
    ]); ?>
</div>

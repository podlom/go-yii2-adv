<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\TinyUrl $model */

$this->title = 'Мої Tiny URLs';
?>

<div class="tiny-url-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <div class="well">
        <h4>Додати новий запис</h4>

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-3"><?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?></div>
            <div class="col-md-5"><?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?></div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Додати', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <h4 class="mt-4">Список записів</h4>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'key',
            'url:url',
            [
                'attribute' => 'comment',
                'format' => 'ntext',
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return date('d.m.Y H:i', $model->created_at);
                }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status === 1 ? 'Активний' : 'Неактивний';
                }
            ],
        ],
    ]); ?>
</div>


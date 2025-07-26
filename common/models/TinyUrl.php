<?php

namespace common\models;


use yii\db\ActiveRecord;
use Yii;


/**
 * This is the model class for table "tiny_url".
 *
 * @property int $id
 * @property string $key
 * @property string $url
 * @property int $created_at
 * @property int $updated_at
 * @property int|null $user_id
 * @property string $comment
 * @property int $status
 */
class TinyUrl extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tiny_url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'url', 'created_at', 'updated_at', 'comment'], 'required'],
            [['created_at', 'updated_at', 'user_id', 'status'], 'integer'],
            [['comment'], 'string'],
            [['key', 'url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'Key'),
            'url' => Yii::t('app', 'Url'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'user_id' => Yii::t('app', 'User ID'),
            'comment' => Yii::t('app', 'Comment'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}

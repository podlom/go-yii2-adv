<?php

declare(strict_types=1);

namespace common\models;

use common\models\TinyUrlQuery;
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
        return '{{%tiny_url}}';
    }

    // IMPORTANT: expose the virtual attribute to Yii
    public function attributes(): array
    {
        return array_merge(parent::attributes(), ['clicks_count']);
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

    // Optional convenience (per-row count; avoid in views to prevent N+1)
    public function getClicksCount(): int
    {
        return (int) $this->getUrlRedirectLogs()->count();
    }

    public static function find(): TinyUrlQuery
    {
        /** @var TinyUrlQuery $q */
        $q = new TinyUrlQuery(static::class);
        return $q->alias('tu');
    }

    // Relation by URL (quick win for current schema)
    public function getUrlRedirectLogs()
    {
        return $this->hasMany(UrlRedirectLog::class, ['tiny_url_id' => 'id']);
    }
}

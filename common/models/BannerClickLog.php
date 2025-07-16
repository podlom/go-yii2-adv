<?php

namespace common\models;

use yii\db\ActiveRecord;
class BannerClickLog extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%banner_click_log}}';
    }

    public function rules(): array
    {
        return [
            [['url', 'created_at'], 'required'],
            [['url', 'user_agent'], 'string'],
            [['created_at'], 'safe'],
            [['ip'], 'string', 'max' => 45],
            [['country', 'city'], 'string', 'max' => 100],
            [['isp'], 'string', 'max' => 255],
            [['network'], 'string', 'max' => 128],
            [['lang'], 'string', 'max' => 2],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord && empty($this->created_at)) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }
}

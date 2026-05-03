<?php

declare(strict_types=1);

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;

class TinyUrlQuery extends ActiveQuery
{
    public function withClicksCount(): self
    {
        // Subquery that aggregates by tiny_url_id
        $logSub = (new Query())
            ->from(UrlRedirectLog::tableName())
            ->select([
                'tiny_url_id',
                'cnt' => new Expression('COUNT(*)'),
            ])
            ->groupBy(['tiny_url_id']);

        // Ensure alias for base table
        $this->alias('tu');

        // IMPORTANT: addSelect (not select) and DO NOT re-select later
        return $this
            ->addSelect(['tu.*', 'clicks_count' => 'COALESCE(ucl.cnt,0)'])
            ->leftJoin(['ucl' => $logSub], 'ucl.tiny_url_id = tu.id');
    }
}

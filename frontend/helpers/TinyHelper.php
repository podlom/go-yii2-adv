<?php

declare(strict_types=1);

namespace frontend\helpers;

use Yii;

class TinyHelper
{
    public static function getUrlToRedirect(string $url, bool $encodeUrl = false): string
    {
        $urlPrefix = Yii::$app->params['app.urlPrefix'] ?? 'https://go.shkodenko.com';

        $fullUrl = $urlPrefix . '/to/' . $url;
        if ($encodeUrl) {
            $fullUrl = $urlPrefix . '/to/' . base64_encode($url);
        }

        return $fullUrl;
    }
}
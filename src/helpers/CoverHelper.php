<?php

namespace kriss\amap\helpers;

use yii\helpers\Json;
use yii\web\JsExpression;

class CoverHelper
{
    public static function coverIcon($icon)
    {
        if (is_string($icon)) {
            $icon = ['image' => $icon];
        }
        if (is_array($icon)) {
            if (isset($icon['size'])) {
                $icon['size'] = static::coverSize($icon['size']);
            }
        }

        $options = Json::encode($icon);
        return new JsExpression("new AMap.Icon({$options})");
    }

    public static function coverSize($size)
    {
        if (is_string($size)) {
            $size = intval($size);
        }
        if (!is_array($size)) {
            $size = [$size, $size];
        }

        return new JsExpression("new AMap.Size({$size[0]}, {$size[1]})");
    }
}
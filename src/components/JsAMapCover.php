<?php

namespace kriss\amap\components;

use yii\base\BaseObject;
use yii\base\NotSupportedException;
use yii\helpers\Json;
use yii\web\JsExpression;

class JsAMapCover extends BaseObject
{
    public function coverMarker($marker)
    {
        $options = $marker;
        if (is_string($options)) {
            throw new NotSupportedException();
        }
        if (isset($options['position'])) {
            $options['position'] = $this->coverLngLat($options['position']);
        }
        if (isset($options['icon'])) {
            $options['icon'] = $this->coverIcon($options['icon']);
        }
        if (isset($options['offset'])) {
            $options['offset'] = $this->coverPixel($options['offset']);
        }

        $options = Json::encode($options);
        new JsExpression("new AMap.Marker({$options})");
    }

    public function coverLngLat($position)
    {
        if (!is_array($position) || count($position) !== 2) {
            throw new NotSupportedException();
        }

        $position = array_map('floatval', $position);
        return new JsExpression("new AMap.LngLat($position[0], $position[1])");
    }

    public function coverPixel($offset)
    {
        if (!is_array($offset) || count($offset) !== 2) {
            throw new NotSupportedException();
        }

        $offset = array_map('floatval', $offset);
        return new JsExpression("new AMap.Pixel($offset[0], $offset[1])");
    }

    public function coverIcon($icon)
    {
        $options = $icon;
        if (is_string($options)) {
            $options = ['image' => $icon];
        }
        if (is_array($options)) {
            if (isset($options['size'])) {
                $options['size'] = $this->coverSize($options['size']);
            }
        }

        $options = Json::encode($icon);
        return new JsExpression("new AMap.Icon({$options})");
    }

    public function coverSize($size)
    {
        if (!is_array($size)) {
            $size = [$size, $size];
        }
        if (count($size) !== 2) {
            throw new NotSupportedException();
        }

        $size = array_map('floatval', $size);
        return new JsExpression("new AMap.Size({$size[0]}, {$size[1]})");
    }
}
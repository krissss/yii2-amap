<?php

namespace kriss\amap\components;

use yii\base\BaseObject;
use yii\web\View;

/**
 * 控制 map 的 url
 * @link https://developer.amap.com/api/javascript-api/guide/abc/load
 */
class JsAMapLoader extends BaseObject
{
    public $mapsUrl = 'https://webapi.amap.com/maps';

    public $key;

    public $version = '1.4.15';

    public $plugins = [];

    public function getMapsUrl()
    {
        $url = "{$this->mapsUrl}?v={$this->version}&key={$this->key}";
        if ($this->plugins) {
            $url .= '&plugin=' . implode(',', $this->plugins);
        }
        return $url;
    }

    /**
     * @param View $view
     */
    public function registerJsUrl($view)
    {
        $view->registerJsFile($this->getMapsUrl());
    }
}
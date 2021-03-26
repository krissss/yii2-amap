<?php

namespace kriss\amap\assets;

use yii\web\AssetBundle;

class MarkerPresetAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/marker1.0';

    public $js = [
        'marker-preset.js',
    ];
}

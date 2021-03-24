<?php

use kriss\amap\widgets\AMapWidget;

$mapInstanceAfter = <<<JS
function resetMarks() {
    $.get('xxx', {}, () => {
        
    })
}
map.on('moveend', resetMarks);
map.on('zoomchange', resetMarks);
JS;

echo AMapWidget::widget([
    'jsAMPLoader' => [
        'class' => \kriss\amap\components\JsAMapLoader::class,
        'key' => 'xxxx',
        'plugins' => [''],
    ],
    'clientOptions' => [
        'zoom' => 5,
        //'center' => [111, 222],
    ],
    'mapInstanceAfter' => $mapInstanceAfter,
]);
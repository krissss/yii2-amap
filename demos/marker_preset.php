<?php

use kriss\amap\assets\MarkerPresetAsset;
use kriss\amap\widgets\AMapWidget;
use yii\helpers\Json;
use yii\web\JqueryAsset;

MarkerPresetAsset::register($this);
JqueryAsset::register($this);

$mapData = [
    [
        'position' => [116.44925, 39.883874],
        'info' => [
            'title' => '金卡司机打开据阿克苏决定卡姐啊思考就',
            'content' => 'askldjkajsdjkaksdk',
            'id' => 111,
        ],
    ],
    [
        'position' => [116.427411, 39.985579],
        'info' => [
            'title' => 'asdkjqkjeqw',
            'content' => '阿斯达斯的',
            'id'=> 222,
        ],
    ],
];
$mapData = Json::encode($mapData);

$mapInstanceAfter = <<<JS
const data = $mapData;

const preset = new MarkerPreset(map, {
    markerOptions: {
        icon: '//a.amap.com/jsapi_demos/static/demo-center/icons/dir-via-marker.png',
    }
})
let template = $('#marker-info-template').clone()
template = template.removeClass('hidden').prop('outerHTML')
for (var i = 0; i < data.length; i++) {
    data[i]['template'] = template
    data[i]['info']['url'] = 'https://www.baidu.com?id=' + data[i]['info']['id']
    preset.addMarker(data[i]);
}
map.setFitView();
JS;

echo AMapWidget::widget([
    'options' => [
        'style' => [
            'width' => '100%',
            'height' => '800px',
        ]
    ],
    'jsAMPLoader' => [
        'key' => 'xxxx',
    ],
    'clientOptions' => [
        'zoom' => 5,
        'center' => [116.44925, 39.883874],
    ],
    'mapInstanceAfter' => $mapInstanceAfter,
]);
?>

<div id="marker-info-template" class="hidden" style="width: 420px">
    <h1>{title}</h1>
    <p>{content}</p>
    <a href="{url}">链接</a>
</div>


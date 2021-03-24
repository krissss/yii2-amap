<?php

namespace kriss\amap\widgets\processors;

use kriss\amap\models\Marker;

class MarkerProcessor extends BaseProcessor
{
    public $markers = [];

    public $icon;

    /**
     * @inheritDoc
     */
    public function process()
    {
        $list = [];
        foreach ($this->markers as $marker) {
            if ($marker instanceof Marker) {
                $marker = $marker->toArray();
            }
            $list[] = '';
        }
        $js = <<<JS
var markers = {$markers};
var icon = new AMap.Icon({
    image: 'https://vdata.amap.com/icons/b18/1/2.png',
    size: new AMap.Size(24, 24)
})
for (var i = 0; i < markers.length; i++) {
    var marker = []
}
var markerList = {$list};map.add(markerList);
JS;
        $this->addJs($js);
    }
}
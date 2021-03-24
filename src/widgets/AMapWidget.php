<?php

namespace kriss\amap\widgets;

use kriss\amap\components\JsAMapLoader;
use kriss\amap\widgets\processors\BaseProcessor;
use yii\base\Widget;
use yii\di\Instance;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * @link https://developer.amap.com/api/javascript-api/summary/
 */
class AMapWidget extends Widget
{
    /**
     * @var JsAMapLoader|array|string
     */
    public $jsAMPLoader;
    /**
     * @var array
     */
    public $options = [
        'style' => [
            'width' => '100%',
            'height' => '100%',
        ]
    ];
    /**
     * @link https://developer.amap.com/api/javascript-api/guide/map/lifecycle#创建地图常用参数
     * @var array
     */
    public $clientOptions = [];
    /**
     * @var BaseProcessor[] array
     */
    public $processors = [];
    /**
     * @var string
     */
    public $clientName = 'myMap';
    /**
     * @var string|array js expression, key 为 js 载入的顺序
     */
    public $mapInstanceAfter = [];

    public function init()
    {
        parent::init();

        $this->jsAMPLoader = Instance::ensure($this->jsAMPLoader, JsAMapLoader::class);

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->id;
        } else {
            $this->id = $this->options['id'];
        }

        if ($this->mapInstanceAfter && is_string($this->mapInstanceAfter)) {
            $this->mapInstanceAfter = [$this->mapInstanceAfter];
        }
    }

    public function run()
    {
        foreach ($this->processors as $processor) {
            $processor = Instance::ensure($processor, BaseProcessor::class);
            $processor->mapWidget = $this;
            $processor->process();
        }

        $this->jsAMPLoader->registerJsUrl($this->view);

        $mapInstanceAfter = '';
        if ($this->mapInstanceAfter) {
            ksort($this->mapInstanceAfter);
            $js = implode("\n", $this->mapInstanceAfter);
            $mapInstanceAfter = new JsExpression("\n(function(map) {{$js}})({$this->clientName});");
        }
        $options = Json::encode($this->clientOptions);
        $js = <<<JS
var {$this->clientName} = new AMap.Map('{$this->id}', {$options});
{$mapInstanceAfter}
JS;
        $this->view->registerJs($js);

        return Html::tag('div', '', $this->options);
    }

    /**
     * @param string $js
     * @param null|int $sort
     */
    public function addMapInstanceAfter($js, $sort = null)
    {
        if ($sort) {
            $this->mapInstanceAfter[$sort] = $js;
        } else {
            $this->mapInstanceAfter[] = $js;
        }
    }
}
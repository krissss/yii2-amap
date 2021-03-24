<?php

namespace kriss\amap\widgets\processors;

use kriss\amap\widgets\AMapWidget;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

abstract class BaseProcessor extends BaseObject
{
    /**
     * @var AMapWidget
     */
    public $mapWidget;

    /**
     * @return void
     */
    abstract public function process();

    public function getClientOption($path, $default = [])
    {
        return ArrayHelper::getValue($this->mapWidget->clientOptions, $path, $default);
    }

    public function setClientOption($path, $value, $merge = true)
    {
        if ($merge) {
            $oldValue = $this->getClientOption($path);
            if ($oldValue) {
                $value = ArrayHelper::merge($oldValue, $value);
            }
        }
        ArrayHelper::setValue($this->mapWidget->clientOptions, $path, $value);
    }

    public function addJs($js, $sort = null)
    {
        $this->mapWidget->addMapInstanceAfter($js, $sort);
    }
}
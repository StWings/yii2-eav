<?php
/**
 * @author Alexey Samoylov <alexey.samoylov@gmail.com>
 */

namespace mazurva\eav\handlers;

use Yii;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Class AttributeHandler
 * @package mazurva\eav
 */
class AttributeHandler extends Widget
{
    const VALUE_HANDLER_CLASS = '\mazurva\eav\handlers\RawValueHandler';
    /** @var EavModel */
    public $owner;
    /** @var ValueHandler */
    public $valueHandler;
    /** @var ActiveRecord */
    public $attributeModel;
    
    public $nameField = 'name';

    /**
     * @param EavModel $owner
     * @param ActiveRecord $attributeModel
     * @return AttributeHandler
     * @throws \yii\base\InvalidConfigException
     */
    public static function load($owner, $attributeModel)
    {
        if (!class_exists($class = $attributeModel->eavType->handlerClass))
            throw new InvalidParamException('Unknown class: ' . $class);

        $handler = Yii::createObject([
            'class' => $class,
            'owner' => $owner,
            'attributeModel' => $attributeModel
        ]);
        $handler->init();

        return $handler;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->valueHandler = Yii::createObject([
            'class' => static::VALUE_HANDLER_CLASS,
            'attributeHandler' => $this,
        ]);
    }

    /**
     * @return string
     */
    public function getAttributeName()
    {
        return strval($this->attributeModel->{$this->nameField});
    }

    public function getOptions()
    {
        $result = [];
        foreach ($this->attributeModel->eavOptions as $option){
            $result[] = $option->getPrimaryKey();
        }
        return $result;
    }
}
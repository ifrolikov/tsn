<?php

namespace app\components\tools\factory;

use yii\base\Model;

/**
 * Class AbstractData
 * @package app\components\tools\factory
 */
abstract class AbstractData extends Model
{
    /**
     * @var array
     */
    private $loadedAttributes = [];

    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();

        foreach ($fields as $field => $value) {
            if (!in_array($field, $this->loadedAttributes)) {
                unset($fields[$field]);
            }
        }
        return $fields;
    }

    /**
     * @param array $values
     * @param bool $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        parent::setAttributes($values, $safeOnly);
        foreach (array_keys($values) as $attribute) {
            $this->loadedAttributes[] = $attribute;
        }

        $this->loadedAttributes = array_unique($this->loadedAttributes);
    }
}
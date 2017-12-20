<?php

namespace app\components\tools\entities;

use yii\base\Model;
use yii\data\BaseDataProvider;

/**
 * Class BaseCollectionEntity
 * @package app\components\tools\entities
 * @depends Model
 */
class BaseCollectionEntity
{
    /**
     * @var
     */
    public $items;
    /**
     * @var
     */
    public $total_count;
    /**
     * @var
     */
    public $page_count;
    /**
     * @var
     */
    public $page;
    /**
     * @var
     */
    public $page_size;

    /**
     * @param BaseDataProvider $provider
     * @param AbstractEntity $entity
     * @return $this
     */
    public function build(BaseDataProvider $provider, AbstractEntity $entity)
    {
        $models = $provider->getModels();
        $this->items = [];
        /** @var Model|array $model */
        foreach ($models as $model) {
            $item = clone $entity;
            if ($model instanceof Model) {
                $model = $model->toArray();
            }
            $item->setAttributes((array)$model);
            $this->items[] = $item;
        }
        $this->total_count = $provider->getTotalCount();
        $this->page_count = $provider->getPagination()->getPageCount();
        $this->page = $provider->getPagination()->getPage();
        $this->page_size = $provider->getPagination()->getPageSize();

        return $this;
    }
}
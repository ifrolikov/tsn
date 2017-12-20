<?php

namespace app\components\tools\factory;

use app\components\tools\entities\AbstractEntity;
use app\components\tools\entities\BaseCollectionEntity;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class Factory
 * @package app\components
 * @depends Model, ActiveDataProvider, BaseDataProvider
 */
class Factory
{
    /**
     * @var AbstractData
     */
    private $data;
    /**
     * @var BaseActiveRecord
     */
    private $model;
    /**
     * @var AbstractEntity
     */
    private $entity;
    /**
     * @var string
     */
    private $validateException;
    /**
     * @var string
     */
    private $exception;
    /**
     * @var array|null
     */
    private $linkedData;
    /**
     * @var string
     */
    private $notFoundException;
    /**
     * @var string
     */
    private $indexProviderClass = ActiveDataProvider::class;
    /**
     * @var int
     */
    private $defaultPageSize = 20;

    /**
     * Factory constructor.
     * @param AbstractData $data
     * @param BaseActiveRecord $model
     * @param AbstractEntity $entity
     * @param array|null $linkedData
     * @param string $validateException
     * @param string $exception
     * @param string $notFoundException
     */
    public function __construct(AbstractData $data = null, BaseActiveRecord $model = null, AbstractEntity $entity = null,
                                array $linkedData = null, string $validateException = UnprocessableEntityHttpException::class,
                                string $exception = \Exception::class, string $notFoundException = NotFoundHttpException::class)
    {
        $this->data = $data;
        $this->model = $model;
        $this->entity = $entity;
        $this->validateException = $validateException;
        $this->exception = $exception;
        $this->linkedData = $linkedData;
        $this->notFoundException = $notFoundException;
    }

    /**
     * @return AbstractEntity
     */
    public function create()
    {
        $this->validateData();
        $this->model->setAttributes($this->data->toArray());
        $this->validateModel();
        if (!$this->model->save(false)) {
            $this->except($this->exception, $this->model);
        }
        $this->fetchLinkedData();
        $this->model->refresh();
        $this->entity->setAttributes($this->model->toArray());
        return $this->entity;
    }

    /**
     * @param mixed $id
     * @return AbstractEntity
     */
    public function update($id)
    {
        $this->validateData();
        if (!$this->model = $this->model::findOne($id)) {
            $this->except($this->notFoundException, $this->data);
        }
        $this->model->setAttributes($this->data->toArray());
        $this->validateModel();
        if (!$this->model->save(false)) {
            $this->except($this->exception, $this->model);
        }
        $this->fetchLinkedData();
        $this->model->refresh();
        $this->entity->setAttributes($this->model->toArray());
        return $this->entity;
    }

    /**
     * @param array $ids
     * @throws \yii\base\NotSupportedException
     */
    public function delete($ids = [])
    {
        if (!$ids) {
            return;
        }

        $pk = $this->model::primaryKey();
        reset($pk);
        $pk = current($pk);
        $this->model::updateAll(['deleted_at' => date('Y-m-d H:i:s')], [$pk => $ids]);
    }

    /**
     * @param BaseCollectionEntity $collection
     * @return BaseCollectionEntity
     */
    public function index(BaseCollectionEntity $collection): BaseCollectionEntity
    {
        if (!(new $this->indexProviderClass()) instanceof BaseDataProvider) {
            $this->except($this->exception, null, 'Data provider must be instance of BaseDataProvider');
        }
        $this->validateData();

        $query = $this->model::find();

        if ($this->model->hasAttribute('deleted_at')) {
            $query->andWhere(['deleted_at' => null]);
        }
        if ($filter = $this->data->toArray()) {
            $query->andWhere($filter);
        }
        /** @var BaseDataProvider $provider */
        $provider = new $this->indexProviderClass([
            'query' => $query,
            'pagination' => [
                'page' => $this->data->hasProperty('page') ? ($this->data->page ?? 0) : 0,
                'pageSize' => $this->data->hasProperty('page_size') ? ($this->data->page_size ?? $this->defaultPageSize) : 0
            ]
        ]);
        $provider->prepare(true);
        return $collection->build($provider, $this->entity);
    }

    /**
     * @return string
     */
    public function getIndexProviderClass(): string
    {
        return $this->indexProviderClass;
    }

    /**
     * @param string $indexProviderClass
     * @return Factory
     */
    public function setIndexProviderClass(string $indexProviderClass): Factory
    {
        $this->indexProviderClass = $indexProviderClass;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultPageSize(): int
    {
        return $this->defaultPageSize;
    }

    /**
     * @param int $defaultPageSize
     * @return Factory
     */
    public function setDefaultPageSize(int $defaultPageSize): Factory
    {
        $this->defaultPageSize = $defaultPageSize;
        return $this;
    }

    /**
     * @return Model
     */
    public function getData()
    {
        return $this->data;
    }

    private function validateData()
    {
        if (!$this->data->validate()) {
            $this->except($this->validateException, $this->data);
        }
    }

    /**
     * @param string|\Exception $class
     * @param Model $model
     * @param string|null $message
     */
    private function except(string $class, Model $model = null, string $message = null)
    {
        throw new $class(isset($model) ? json_encode($model->errors, JSON_UNESCAPED_UNICODE) : ($message ?? ''));
    }

    private function validateModel()
    {
        if (!$this->model->validate()) {
            $this->except($this->validateException, $this->model);
        }
    }

    private function fetchLinkedData()
    {
        if (!$this->linkedData) {
            return;
        }

        foreach ($this->linkedData as $data) {
            if ($this->data->hasProperty($data->link)) {
                $data->factory->getData()->setAttributes($this->data->toArray()[$data->link]);
            }
        }
    }
}
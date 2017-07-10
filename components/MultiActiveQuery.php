<?php

namespace components;


use yii\db\ActiveQuery;

/**
 * Class MultiActiveQuery
 *
 * @package components
 * @property integer[] targetTenants
 */
class MultiActiveQuery extends ActiveQuery
{
    public $targetTenants;
    public $modelClassAlias;

    /**
     * MultiActiveQuery has to preserve the alias in order to prepare correct tenant injection in prepare() method.
     *
     * @param string $alias
     *
     * @return $this
     */
    public function alias($alias)
    {
        $this->modelClassAlias = $alias;
        parent::alias($alias);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function prepare($builder)
    {
        $query = parent::prepare($builder);
        /* @var $modelClass MultiActiveRecord */

        if (empty($this->modelClassAlias)) {
            $modelClass = $this->modelClass;
            $this->modelClassAlias = $modelClass::tableName();
        }

        if (empty($this->targetTenants)) {
            $this->targetTenants = [CURRENT_TENANT_ID];
        }
        $query->andWhere(["{$this->modelClassAlias}.tenant" => $this->targetTenants]);


        return $query;
    }

    /**
     * @param integer[] $tenantIds
     *
     * @return $this
     */
    public function acrossTenants($tenantIds)
    {
        $this->targetTenants = $tenantIds;

        return $this;
    }
}
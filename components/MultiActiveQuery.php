<?php

namespace components;


use yii\db\ActiveQuery;

/**
 * Class MultiActiveQuery
 *
 * @package components
 * @property integer[] $targetSites
 */
class MultiActiveQuery extends ActiveQuery
{
    public $targetSites;
    public $modelClassAlias;

    /**
     * MultiActiveQuery has to preserve the alias in order to prepare correct site injection in prepare() method.
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

        if (empty($this->targetSites)) {
            $this->targetSites = [CURRENT_TENANT_ID];
        }
        $query->andWhere(["{$this->modelClassAlias}.site" => $this->targetSites]);


        return $query;
    }

    /**
     * @param integer[] $siteIds
     *
     * @return $this
     */
    public function acrossSites($siteIds)
    {
        $this->targetSites = $siteIds;

        return $this;
    }
}
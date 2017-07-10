<?php

namespace components;


use Yii;
use yii\db\ActiveRecord;

/**
 * Class MultiActiveRecord
 *
 * @package components
 * @property int $tenant
 */
class MultiActiveRecord extends ActiveRecord
{
    public function init()
    {
        $this->tenant = CURRENT_TENANT_ID;

        parent::init();
    }

    public function rules()
    {
        return [['tenant', 'required']];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return Yii::createObject(MultiActiveQuery::className(), [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public static function updateAll($attributes, $condition = '', $params = [])
    {
        return parent::updateAll($attributes, self::addTenantFilter($condition), $params);
    }

    /**
     * @inheritdoc
     */
    public static function updateAllCounters($attributes, $condition = '', $params = [])
    {
        return parent::updateAllCounters($attributes, self::addTenantFilter($condition), $params);
    }

    /**
     * @inheritdoc
     */
    public static function deleteAll($condition = '', $params = [])
    {
        return parent::deleteAll(self::addTenantFilter($condition), $params);
    }

    /**
     * @param $condition
     *
     * @return array|string
     */
    protected static function addTenantFilter($condition)
    {
        if (defined('CURRENT_TENANT_ID')) {
            switch (true) {
                case is_string($condition):
                    if (empty($condition)) {
                        $condition = 'tenant = ' . CURRENT_TENANT_ID;
                    } else {
                        $condition .= ' AND tenant = ' . CURRENT_TENANT_ID;
                    }
                    break;
                case is_array($condition):
                    $condition = array_merge($condition, ['tenant' => CURRENT_TENANT_ID]);
                    break;
            }
        }

        return $condition;
    }
}
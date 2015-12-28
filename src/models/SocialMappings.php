<?php

namespace DevGroup\Users\models;

use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class SocialMappings mappings from social service information to user model.
 *
 * @package DevGroup\Users\models
 * @property integer $id
 * @property integer $social_service_id
 * @property string  $model_attribute
 * @property string  $social_attributes
 */
class SocialMappings extends ActiveRecord
{
    use TagDependencyTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'CacheableActiveRecord' => [
                'class' => CacheableActiveRecord::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_mappings}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_attribute','social_attributes'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_attribute' => Yii::t('users', 'User attribute'),
            'social_attributes' => Yii::t('users', 'Social service attributes'),
        ];
    }
}

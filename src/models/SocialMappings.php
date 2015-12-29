<?php

namespace DevGroup\Users\models;

use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\LazyCache;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use Yii;
use yii\caching\TagDependency;
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

    /**
     * @param integer $id
     *
     * @return array
     */
    public static function mapForSocialService($id)
    {
        /** @var LazyCache $cache */
        $cache = Yii::$app->cache;
        $map = $cache->lazy(
            function () use($id) {
                return static::find()
                    ->where(['social_service_id' => $id])
                    ->indexBy('model_attribute')
                    ->select(['model_attribute', 'social_attributes'])
                    ->asArray()
                    ->all();
            },
            "MappingsForSocial:$id",
            86400,
            new TagDependency([
                'tags' => [
                    static::commonTag(),
                ]
            ])
        );
        foreach ($map as &$values) {
            $values = explode(',', $values['social_attributes']);
        }
        return $map;
    }
}

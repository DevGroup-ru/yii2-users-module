<?php

namespace DevGroup\Users\models;

use DevGroup\AdminUtils\validators\ClassnameValidator;
use DevGroup\DataStructure\behaviors\PackedJsonAttributes;
use DevGroup\Multilingual\behaviors\MultilingualActiveRecord;
use DevGroup\Multilingual\Multilingual;
use DevGroup\Multilingual\traits\MultilingualTrait;
use DevGroup\TagDependencyHelper\CacheableActiveRecord;
use DevGroup\TagDependencyHelper\LazyCache;
use DevGroup\TagDependencyHelper\TagDependencyTrait;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * Class SocialServiceInterface represents social service type
 *
 * @package DevGroup\Users\models
 * @property integer $id
 * @property string $bem_modifier
 * @property string $class_name
 * @property string $packed_json_params
 * @property integer $sort_order
 */
class SocialService extends ActiveRecord
{
    public static $classNameToId = [];
    public static $translatedNameById = [];

    use TagDependencyTrait;
    use MultilingualTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'json_attributes' => [
                'class' => PackedJsonAttributes::class,
            ],
            'CacheableActiveRecord' => [
                'class' => CacheableActiveRecord::class,
            ],
            'multilingual' => [
                'class' => MultilingualActiveRecord::class,
                'translationPublishedAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bem_modifier', 'class_name'], 'string'],
            [['sort_order'], 'integer'],
            [
                ['class_name'],
                ClassnameValidator::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'class_name' => Yii::t('users', 'AuthClient class name'),
            'bem_modifier' => Yii::t('users', 'BEM modifier'),
            'params' => Yii::t('users', 'AuthClient parameters'),
        ];
    }

    public static function classNameToId($className)
    {
        if (isset(static::$classNameToId[$className]) === false) {

            /** @var LazyCache $cache */
            $cache = Yii::$app->cache;

            static::$classNameToId[$className] = $cache->lazy(
                function () use ($className) {
                    return static::find()
                        ->where(['class_name'=>$className])
                        ->select(['id'])
                        ->scalar();
                },
                "SocialService:classname2id:$className",
                86400,
                new TagDependency([
                    'tags' => static::commonTag(),
                ])
            );
        }
        return intval(static::$classNameToId[$className]);
    }

    public static function i18nNameById($id)
    {
        if (isset(static::$translatedNameById[$id]) === false) {

            /** @var LazyCache $cache */
            $cache = Yii::$app->cache;

            static::$translatedNameById[$id] = $cache->lazy(
                function () use ($id) {
                    /** @var Multilingual $multilingual */
                    $multilingual = Yii::$app->multilingual;
                    return SocialServiceTranslation::find()
                        ->where(['model_id'=>$id, 'language_id' => $multilingual->language_id])
                        ->select(['name'])
                        ->scalar();
                },
                "SocialService:id2name-i18n:$id",
                86400,
                new TagDependency([
                    'tags' => static::commonTag(),
                ])
            );
        }
        return empty(static::$translatedNameById[$id]) ? '(not set)' : static::$translatedNameById[$id];
    }
}

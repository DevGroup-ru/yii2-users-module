<?php

namespace DevGroup\Users\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class SocialServiceTranslation.php represents social service type translation
 *
 * @package DevGroup\Users\models
 * @property integer $id
 * @property string $bem_modifier
 * @property string $class_name
 * @property string $packed_json_params
 * @property integer $sort_order
 */
class SocialServiceTranslation extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_service_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('users', 'Name'),
        ];
    }
}

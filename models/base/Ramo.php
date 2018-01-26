<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "ramo".
 *
 * @property int $id
 * @property string $ram_descricao
 * @property string $ram_status
 *
 * @property ModalidadeValorlimite[] $modalidadeValorlimites
 */
class Ramo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ramo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ram_descricao', 'ram_status'], 'required'],
            [['ram_descricao'], 'string', 'max' => 255],
            [['ram_status'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ram_descricao' => 'Ram Descricao',
            'ram_status' => 'Ram Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModalidadeValorlimites()
    {
        return $this->hasMany(ModalidadeValorlimite::className(), ['ramo_id' => 'id']);
    }
}

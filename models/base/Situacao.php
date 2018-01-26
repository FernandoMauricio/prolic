<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "situacao".
 *
 * @property int $id
 * @property string $sit_descricao
 * @property int $sit_status
 *
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class Situacao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'situacao';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sit_descricao', 'sit_status'], 'required'],
            [['sit_status'], 'integer'],
            [['sit_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sit_descricao' => 'Sit Descricao',
            'sit_status' => 'Sit Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorios()
    {
        return $this->hasMany(ProcessoLicitatorio::className(), ['situacao_id' => 'id']);
    }
}

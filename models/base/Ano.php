<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "ano".
 *
 * @property int $id
 * @property int $an_ano
 * @property int $an_status
 *
 * @property ModalidadeValorlimite[] $modalidadeValorlimites
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class Ano extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ano';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['an_ano', 'an_status'], 'required'],
            [['an_ano', 'an_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'an_ano' => 'Ano',
            'an_status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModalidadeValorlimites()
    {
        return $this->hasMany(ModalidadeValorlimite::className(), ['ano_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorios()
    {
        return $this->hasMany(ProcessoLicitatorio::className(), ['ano_id' => 'id']);
    }
}

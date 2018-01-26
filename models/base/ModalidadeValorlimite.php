<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "modalidade_valorlimite".
 *
 * @property int $id
 * @property int $modalidade_id
 * @property int $ramo_id
 * @property int $ano_id
 * @property double $valor_limite
 * @property int $status
 *
 * @property Ano $ano
 * @property Modalidade $modalidade
 * @property Ramo $ramo
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class ModalidadeValorlimite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modalidade_valorlimite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modalidade_id', 'ramo_id', 'ano_id', 'valor_limite', 'status'], 'required'],
            [['modalidade_id', 'ramo_id', 'ano_id', 'status'], 'integer'],
            [['valor_limite'], 'number'],
            [['ano_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ano::className(), 'targetAttribute' => ['ano_id' => 'id']],
            [['modalidade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidade::className(), 'targetAttribute' => ['modalidade_id' => 'id']],
            [['ramo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ramo::className(), 'targetAttribute' => ['ramo_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modalidade_id' => 'Modalidade ID',
            'ramo_id' => 'Ramo ID',
            'ano_id' => 'Ano ID',
            'valor_limite' => 'Valor Limite',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAno()
    {
        return $this->hasOne(Ano::className(), ['id' => 'ano_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModalidade()
    {
        return $this->hasOne(Modalidade::className(), ['id' => 'modalidade_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRamo()
    {
        return $this->hasOne(Ramo::className(), ['id' => 'ramo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorios()
    {
        return $this->hasMany(ProcessoLicitatorio::className(), ['modalidade_valorlimite_id' => 'id']);
    }
}

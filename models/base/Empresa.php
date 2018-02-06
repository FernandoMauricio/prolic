<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "empresa".
 *
 * @property int $id
 * @property string $emp_descricao
 * @property int $emp_status
 *
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class Empresa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'empresa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emp_descricao', 'emp_status'], 'required'],
            [['emp_status'], 'integer'],
            [['emp_descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emp_descricao' => 'Nome da Empresa',
            'emp_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorios()
    {
        return $this->hasMany(ProcessoLicitatorio::className(), ['empresa_id' => 'id']);
    }
}

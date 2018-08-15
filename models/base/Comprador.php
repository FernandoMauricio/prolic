<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "comprador".
 *
 * @property int $id
 * @property string $comp_descricao
 * @property int $comp_status
 *
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class Comprador extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comprador';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comp_descricao', 'comp_status'], 'required'],
            [['comp_status'], 'integer'],
            [['comp_descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'comp_descricao' => 'Nome do Comprador',
            'comp_status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorios()
    {
        return $this->hasMany(ProcessoLicitatorio::className(), ['comprador_id' => 'id']);
    }
}

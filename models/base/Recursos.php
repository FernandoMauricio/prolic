<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "recursos".
 *
 * @property int $id
 * @property string $rec_descricao
 * @property int $rec_status
 *
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class Recursos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recursos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rec_descricao', 'rec_status'], 'required'],
            [['rec_status'], 'integer'],
            [['rec_descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'rec_descricao' => 'Descrição',
            'rec_status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorios()
    {
        return $this->hasMany(ProcessoLicitatorio::className(), ['recursos_id' => 'id']);
    }
}

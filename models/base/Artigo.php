<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "artigo".
 *
 * @property int $id
 * @property string $art_descricao
 * @property int $art_status
 *
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class Artigo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'artigo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['art_descricao', 'art_status'], 'required'],
            [['art_status'], 'integer'],
            [['art_descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'art_descricao' => 'Descrição',
            'art_status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorios()
    {
        return $this->hasMany(ProcessoLicitatorio::className(), ['artigo_id' => 'id']);
    }
}

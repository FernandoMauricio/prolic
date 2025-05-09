<?php

namespace app\models\base;

use app\models\processolicitatorio\ProcessoLicitatorio;
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
            [['art_descricao', 'art_tipo', 'art_status'], 'required'],
            [['art_status'], 'integer'],
            [['art_homologacaodata'], 'safe'],
            [['art_descricao', 'art_homologacaousuario'], 'string', 'max' => 255],
            [['art_tipo'], 'string', 'max' => 25],
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
            'art_tipo' => 'Tipo',
            'art_homologacaousuario' => 'Usuário Homologação',
            'art_homologacaodata' => 'Data Homologação',
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

<?php

namespace app\models\processolicitatorio;

use Yii;

/**
 * This is the model class for table "observacoes".
 *
 * @property int $id
 * @property string $obs_descricao
 * @property string $obs_usuariocriacao
 * @property string $obs_datacriacao
 * @property int $processo_licitatorio_id
 *
 * @property ProcessoLicitatorio $processoLicitatorio
 */
class Observacoes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'observacoes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['processo_licitatorio_id'], 'required'],
            [['id', 'processo_licitatorio_id'], 'integer'],
            [['obs_datacriacao'], 'safe'],
            [['obs_descricao'], 'string', 'max' => 255],
            [['obs_usuariocriacao'], 'string', 'max' => 45],
            [['id'], 'unique'],
            [['processo_licitatorio_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProcessoLicitatorio::className(), 'targetAttribute' => ['processo_licitatorio_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'obs_descricao' => 'Descreva sua observação',
            'obs_usuariocriacao' => 'Usuário',
            'obs_datacriacao' => 'Data',
            'processo_licitatorio_id' => 'Processo Licitatorio ID',
        ];
    }

    public function getCountObservacoes($id)
    {

        $model = Observacoes::find()->where(['processo_licitatorio_id' => $id])->count();

        return $model;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProcessoLicitatorio()
    {
        return $this->hasOne(ProcessoLicitatorio::className(), ['id' => 'processo_licitatorio_id']);
    }
}

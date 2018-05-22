<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "modalidade".
 *
 * @property int $id
 * @property string $mod_descricao
 * @property int $mod_status
 *
 * @property ModalidadeValorlimite[] $modalidadeValorlimites
 */
class Modalidade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modalidade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mod_descricao', 'mod_status'], 'required'],
            [['mod_status'], 'integer'],
            [['mod_descricao'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'mod_descricao' => 'Descrição',
            'mod_status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModalidadeValorlimites()
    {
        return $this->hasMany(ModalidadeValorlimite::className(), ['modalidade_id' => 'id']);
    }
}

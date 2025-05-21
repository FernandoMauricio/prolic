<?php

namespace app\models\mxm;

use Yii;

/**
 * Este é o model para a tabela "ITPEDCOMPRA_IPC".
 */
class ItpedcompraIpc extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'ITPEDCOMPRA_IPC';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_oracle');
    }

    public function rules()
    {
        return [
            [['IRC_NUMERO', 'IRC_CDEMPRESA', 'IRC_REQUISIC'], 'string', 'max' => 6],
            [['IRC_NUMITEM'], 'integer'],
            [['IRC_QTDPEDIDA', 'IRC_VALOR'], 'number'],
            [['IRC_DESCRICAO'], 'string', 'max' => 150],
            [['IRC_ITEM'], 'string', 'max' => 15],
            [['IRC_UNIDADE'], 'string', 'max' => 10],
        ];
    }

    public function attributeLabels()
    {
        return [
            'IRC_NUMERO' => 'Número',
            'IRC_CDEMPRESA' => 'Empresa',
            'IRC_REQUISIC' => 'Requisição',
            'IRC_NUMITEM' => 'Item Nº',
            'IRC_ITEM' => 'Código do Item',
            'IRC_DESCRICAO' => 'Descrição',
            'IRC_UNIDADE' => 'Unidade',
            'IRC_QTDPEDIDA' => 'Quantidade',
            'IRC_VALOR' => 'Preço',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        foreach ($this->attributes as $k => $v) {
            if (is_string($v)) {
                $this->$k = mb_convert_encoding($v, 'UTF-8', 'Windows-1252');
            }
        }
    }

    /**
     * Relacionamento com a requisição
     */
    public function getRequisicao()
    {
        return $this->hasOne(ReqcompraRco::class, [
            'RCO_NUMERO' => 'IRC_REQUISIC',
            'RCO_EMPRESA' => 'IRC_CDEMPRESA'
        ]);
    }
}

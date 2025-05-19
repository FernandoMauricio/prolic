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
            [['IPC_NUMERO', 'IPC_CDEMPRESA', 'IPC_REQUISIC'], 'string', 'max' => 6],
            [['IPC_NUMITEM'], 'integer'],
            [['IPC_QTD', 'IPC_PRECO', 'IPC_PRECOSEMIMP', 'IPC_VLDESCONTO'], 'number'],
            [['IPC_DESCRICAO'], 'string', 'max' => 150],
            [['IPC_ITEM'], 'string', 'max' => 15],
            [['IPC_UNIDADE'], 'string', 'max' => 10],
            [['IPC_DTPARAENT'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'IPC_NUMERO' => 'Número',
            'IPC_CDEMPRESA' => 'Empresa',
            'IPC_REQUISIC' => 'Requisição',
            'IPC_NUMITEM' => 'Item Nº',
            'IPC_ITEM' => 'Código do Item',
            'IPC_DESCRICAO' => 'Descrição',
            'IPC_UNIDADE' => 'Unidade',
            'IPC_QTD' => 'Quantidade',
            'IPC_PRECO' => 'Preço',
            'IPC_PRECOSEMIMP' => 'Preço s/ Impostos',
            'IPC_VLDESCONTO' => 'Desconto',
            'IPC_DTPARAENT' => 'Data para Entrega',
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
            'RCO_NUMERO' => 'IPC_REQUISIC',
            'RCO_EMPRESA' => 'IPC_CDEMPRESA'
        ]);
    }
}

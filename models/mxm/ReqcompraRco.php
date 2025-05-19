<?php

namespace app\models\mxm;

use yii\db\ActiveRecord;

/**
 * Model para a tabela REQCOMPRA_RCO no Oracle.
 *
 * @property string $RCO_NUMERO
 * @property string $RCO_DATA
 * @property string $RCO_EMPRESA
 * @property string $RCO_TIPO
 * @property string $RCO_SETOR
 * @property string $RCO_REQUISITANTE
 * @property string $RCO_POSFUNC
 * @property string $RCO_OBS
 * @property string $RCO_DTMOV
 * @property int    $RCO_ETAPE
 * @property string $RCO_TPOPER
 * @property string $RCO_REQCOMPRA
 * @property string $RCO_USUARIO
 * @property string $RCO_ORDEM
 * @property string $RCO_EXP
 * @property string $RCO_JUSTIFICATIVA
 * @property string $RCO_DEVOLUCAO
 * @property string $RCO_ORDPCP
 * @property string $RCO_MOEDA
 * @property int    $RCO_PROTOCOLO
 * @property string $RCO_FORAPRAZO
 * @property string $RCO_STATUSAPRV
 * @property string $RCO_IMPRESSO
 * @property string $RCO_ENCERRAM
 * @property string $RCO_DESTINACAO
 * @property string $RCO_CCUSTO
 * @property int    $RCO_CDORDEMSERVICO
 * @property string $RCO_DTLIMITERECEBIMENTOITENS
 * @property string $RCO_DTINCLUSAO
 * @property string $RCO_USINCLUSAO
 * @property string $RCO_DTALTERACAO
 * @property string $RCO_USALTERACAO
 * @property float  $RCO_VLCOTACAO
 * @property string $RCO_VBEXIGEPESPRE
 */
class ReqcompraRco extends ActiveRecord
{
    public static function tableName()
    {
        return 'REQCOMPRA_RCO';
    }

    public static function getDb()
    {
        return \Yii::$app->get('db_oracle');
    }

    public function rules()
    {
        return [
            [['RCO_NUMERO', 'RCO_DATA', 'RCO_EMPRESA', 'RCO_TIPO', 'RCO_DTMOV', 'RCO_REQCOMPRA', 'RCO_USUARIO', 'RCO_DEVOLUCAO', 'RCO_MOEDA'], 'required'],
            [['RCO_DATA', 'RCO_DTMOV', 'RCO_DTLIMITERECEBIMENTOITENS', 'RCO_DTINCLUSAO', 'RCO_DTALTERACAO'], 'safe'],
            [['RCO_ETAPE', 'RCO_PROTOCOLO', 'RCO_CDORDEMSERVICO'], 'integer'],
            [['RCO_VLCOTACAO'], 'number'],
            [['RCO_JUSTIFICATIVA'], 'string', 'max' => 1500],
            [['RCO_NUMERO'], 'string', 'max' => 6],
            [['RCO_EMPRESA'], 'string', 'max' => 4],
            [['RCO_TIPO', 'RCO_TPOPER'], 'string', 'max' => 10],
            [['RCO_SETOR'], 'string', 'max' => 10],
            [['RCO_REQUISITANTE', 'RCO_USUARIO', 'RCO_USINCLUSAO', 'RCO_USALTERACAO'], 'string', 'max' => 30],
            [['RCO_POSFUNC'], 'string', 'max' => 20],
            [['RCO_OBS'], 'string', 'max' => 100],
            [['RCO_REQCOMPRA', 'RCO_EXP', 'RCO_DEVOLUCAO', 'RCO_FORAPRAZO', 'RCO_IMPRESSO', 'RCO_VBEXIGEPESPRE'], 'string', 'max' => 1],
            [['RCO_ORDEM'], 'string', 'max' => 38],
            [['RCO_ORDPCP', 'RCO_DESTINACAO', 'RCO_CCUSTO'], 'string', 'max' => 15],
            [['RCO_STATUSAPRV'], 'string', 'max' => 3],
            [['RCO_ENCERRAM'], 'string', 'max' => 2],
        ];
    }

    public function attributeLabels()
    {
        return [
            'RCO_NUMERO' => 'Número',
            'RCO_DATA' => 'Data',
            'RCO_EMPRESA' => 'Empresa',
            'RCO_TIPO' => 'Tipo',
            'RCO_SETOR' => 'Setor',
            'RCO_REQUISITANTE' => 'Requisitante',
            'RCO_POSFUNC' => 'Posição Funcional',
            'RCO_OBS' => 'Observação',
            'RCO_DTMOV' => 'Data de Movimentação',
            'RCO_ETAPE' => 'Etapa',
            'RCO_TPOPER' => 'Tipo Operação',
            'RCO_REQCOMPRA' => 'É Requisição?',
            'RCO_USUARIO' => 'Usuário',
            'RCO_ORDEM' => 'Ordem',
            'RCO_EXP' => 'Exportação',
            'RCO_JUSTIFICATIVA' => 'Justificativa',
            'RCO_DEVOLUCAO' => 'Devolução',
            'RCO_ORDPCP' => 'Ordem PCP',
            'RCO_MOEDA' => 'Moeda',
            'RCO_PROTOCOLO' => 'Protocolo',
            'RCO_FORAPRAZO' => 'Fora do Prazo',
            'RCO_STATUSAPRV' => 'Status Aprov.',
            'RCO_IMPRESSO' => 'Impresso',
            'RCO_ENCERRAM' => 'Encerramento',
            'RCO_DESTINACAO' => 'Destinação',
            'RCO_CCUSTO' => 'Centro de Custo',
            'RCO_CDORDEMSERVICO' => 'Código Ordem de Serviço',
            'RCO_DTLIMITERECEBIMENTOITENS' => 'Data Limite Recebimento',
            'RCO_DTINCLUSAO' => 'Data Inclusão',
            'RCO_USINCLUSAO' => 'Usuário Inclusão',
            'RCO_DTALTERACAO' => 'Data Alteração',
            'RCO_USALTERACAO' => 'Usuário Alteração',
            'RCO_VLCOTACAO' => 'Valor Cotação',
            'RCO_VBEXIGEPESPRE' => 'Exige Pesquisa de Preço',
        ];
    }
}

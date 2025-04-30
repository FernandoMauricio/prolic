<?php

namespace app\models\processolicitatorio;

use Yii;
use app\models\base\Ano;
use app\models\base\Artigo;
use app\models\base\Comprador;
use app\models\base\ModalidadeValorlimite;
use app\models\base\Recursos;
use app\models\base\Situacao;
use app\models\base\Unidades;
use app\models\base\Centrocusto;
use app\models\base\Empresa;
use yii\db\Expression;

/**
 * This is the model class for table "processo_licitatorio".
 *
 * @property int $id
 * @property int $ano_id
 * @property string $prolic_objeto
 * @property int $prolic_codmxm
 * @property string|null $prolic_destino
 * @property int $modalidade_valorlimite_id
 * @property int $prolic_sequenciamodal
 * @property int $artigo_id
 * @property int $prolic_cotacoes
 * @property string|null $prolic_centrocusto
 * @property string $prolic_elementodespesa
 * @property double $prolic_valorestimado
 * @property double $prolic_valoraditivo
 * @property double $prolic_valorefetivo
 * @property int $recursos_id
 * @property int $comprador_id
 * @property string $prolic_datacertame
 * @property string $prolic_datadevolucao
 * @property int $situacao_id
 * @property string $prolic_datahomologacao
 * @property string $prolic_motivo
 * @property int|null $empresa_id
 * @property string $prolic_usuariocriacao
 * @property string $prolic_datacriacao
 * @property string $prolic_usuarioatualizacao
 * @property string $prolic_dataatualizacao
 *
 * @property Observacoes[] $observacoes
 * @property Ano $ano
 * @property Artigo $artigo
 * @property Comprador $comprador
 * @property ModalidadeValorlimite $modalidadeValorlimite
 * @property Recursos $recursos
 * @property Situacao $situacao
 */
class ProcessoLicitatorio extends \yii\db\ActiveRecord
{
    public $modalidade;
    public $ramo;
    public $valor_limite;
    public $valor_limite_apurado;
    public $valor_saldo;
    public $valor_limite_hidden;
    public $valor_limite_apurado_hidden;
    public $valor_saldo_hidden;
    public $prolic_valorefetivo_hidden;
    public $ciclototal;
    public $ciclocertame;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'processo_licitatorio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ano_id', 'prolic_objeto', 'prolic_codmxm', 'prolic_destino', 'modalidade_valorlimite_id', 'prolic_sequenciamodal', 'artigo_id', 'recursos_id', 'comprador_id', 'situacao_id', 'prolic_usuariocriacao', 'prolic_datacriacao', 'prolic_centrocusto'], 'required'],
            [['ano_id', 'modalidade_valorlimite_id', 'prolic_sequenciamodal', 'artigo_id', 'prolic_cotacoes', 'recursos_id', 'comprador_id', 'situacao_id', 'prolic_codprocesso'], 'integer'],
            [['prolic_objeto', 'prolic_elementodespesa', 'prolic_motivo', 'prolic_codmxm'], 'string'],
            [['prolic_valorestimado', 'prolic_valoraditivo', 'prolic_valorefetivo', 'valor_limite', 'valor_limite_apurado', 'valor_saldo', 'valor_limite_hidden', 'valor_limite_apurado_hidden', 'valor_saldo_hidden', 'prolic_valorefetivo_hidden'], 'number'],
            [['prolic_dataprocesso', 'prolic_datacertame', 'prolic_datadevolucao', 'prolic_datahomologacao', 'prolic_datacriacao', 'prolic_dataatualizacao', 'prolic_destino', 'prolic_centrocusto', 'modalidade', 'ramo', 'ciclototal', 'ciclocertame', 'prolic_empresa'], 'safe'],
            [['prolic_usuariocriacao', 'prolic_usuarioatualizacao'], 'string', 'max' => 255],
            [['ano_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ano::className(), 'targetAttribute' => ['ano_id' => 'id']],
            [['artigo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artigo::className(), 'targetAttribute' => ['artigo_id' => 'id']],
            [['comprador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comprador::className(), 'targetAttribute' => ['comprador_id' => 'id']],
            [['modalidade_valorlimite_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModalidadeValorlimite::className(), 'targetAttribute' => ['modalidade_valorlimite_id' => 'id']],
            [['recursos_id'], 'exist', 'skipOnError' => true, 'targetClass' => Recursos::className(), 'targetAttribute' => ['recursos_id' => 'id']],
            [['situacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Situacao::className(), 'targetAttribute' => ['situacao_id' => 'id']],
            ['prolic_codmxm', 'unique', 'on' => 'insert'],
            // ['prolic_valorestimado', 'compare', 'compareAttribute' => 'valor_limite_hidden', 'operator' => '<=', 'type' => 'number', 'when' => function($model) {               
            //             return $model->artigo->art_tipo == 'Valor';                 
            //         }, 'whenClient' => "function (attribute, value) {
            //     return $('#processolicitatorio-artigo_id').val() == 'Valor';
            // }", 'message' => 'Ultrapassa valor limite!'
            // ],
            // ['prolic_valorestimado', 'compare', 'compareAttribute' => 'valor_saldo_hidden', 'operator' => '<=', 'type' => 'number', 'when' => function($model) {               
            //             return $model->artigo->art_tipo == 'Valor';                 
            //         }, 'whenClient' => "function (attribute, value) {
            //     return $('#processolicitatorio-artigo_id').val() == 'Valor';
            // }", 'message' => 'Modalidade sem saldo!'
            // ],
            // ['prolic_valoraditivo', 'compare', 'compareAttribute' => 'valor_limite_hidden', 'operator' => '<=', 'type' => 'number', 'when' => function($model) {               
            //             return $model->artigo->art_tipo == 'Valor';                 
            //         }, 'whenClient' => "function (attribute, value) {
            //     return $('#processolicitatorio-artigo_id').val() == 'Valor';
            // }", 'message' => 'Ultrapassa valor limite!'],
            // ['prolic_valoraditivo', 'compare', 'compareAttribute' => 'valor_saldo_hidden', 'operator' => '<=', 'type' => 'number', 'when' => function($model) {               
            //             return $model->artigo->art_tipo == 'Valor';                 
            //         }, 'whenClient' => "function (attribute, value) {
            //     return $('#processolicitatorio-artigo_id').val() == 'Valor';
            // }", 'message' => 'Modalidade sem saldo!'
            // ],
            [
                'prolic_valorefetivo',
                'compare',
                'compareAttribute' => 'valor_limite_hidden',
                'operator' => '<=',
                'type' => 'number',
                'when' => function ($model) {
                    return $model->artigo->art_tipo == 'Valor' && $model->modalidadeValorlimite->tipo == 0;
                },
                'whenClient' => "function (attribute, value) {
                return $('#processolicitatorio-artigo_id').val() == 'Valor';
            }",
                'message' => 'Ultrapassa valor limite!'
            ],
            [
                'prolic_valorefetivo',
                'compare',
                'compareAttribute' => 'valor_saldo_hidden',
                'operator' => '<=',
                'type' => 'number',
                'when' => function ($model) {
                    return $model->artigo->art_tipo == 'Valor' && $model->modalidadeValorlimite->tipo == 0;
                },
                'whenClient' => "function (attribute, value) {
                return $('#processolicitatorio-artigo_id').val() == 'Valor';
            }",
                'message' => 'Modalidade sem saldo!'
            ],
        ];
    }

    public function getCicloTotal()
    {
        if ($this->prolic_datahomologacao && $this->prolic_dataprocesso) {
            return (new \DateTime($this->prolic_datahomologacao))->diff(new \DateTime($this->prolic_dataprocesso))->days;
        }
        return null;
    }

    public function getCicloCertame()
    {
        if ($this->prolic_datahomologacao && $this->prolic_datacertame) {
            return (new \DateTime($this->prolic_datahomologacao))->diff(new \DateTime($this->prolic_datacertame))->days;
        }
        return null;
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['insert'] = ['prolic_codmxm']; //Scenario Values Only Accepted
        return $scenarios;
    }

    //Busca dados dos valores limites de cada modalidade
    public static function getLimiteSubCat($cat_id)
    {
        $data = ModalidadeValorlimite::find()
            ->joinWith('ramo', false, 'INNER JOIN')
            ->where(['modalidade_id' => $cat_id])
            ->andWhere(['IS NOT', 'homologacao_usuario', null]) //Localiza apenas Modalidades homologadas
            ->andWhere(['=', 'ano_id', 8]) //ano corrente 2025
            ->select(['modalidade_valorlimite.id AS id', 'ram_descricao AS name'])->asArray()->all();
        return $data;
    }

    //Localiza a somatório dos Limites e o Saldo
    public static function getSumLimite($cat_id, $processo)
    {
        $data = ProcessoLicitatorio::find()
            ->joinWith('modalidadeValorlimite', false, 'LEFT JOIN')
            ->where(['modalidade_valorlimite.id' => $cat_id])
            ->andWhere(['NOT IN', 'processo_licitatorio.id', [$processo]])
            ->andWhere(['!=', 'situacao_id', 7]) //Exclui processos licitatórios que foram cancelados
            ->select([
                'modalidade_valorlimite.valor_limite', // Inclua a coluna valor_limite explicitamente
                'sum(prolic_valorestimado + prolic_valoraditivo) AS valor_limite_apurado',
                'modalidade_valorlimite.valor_limite - sum(prolic_valorestimado + prolic_valoraditivo) AS valor_saldo'
            ])->asArray()->one();

        // Verifique se o valor está sendo retornado corretamente
        $valorLimite = ModalidadeValorlimite::findOne($cat_id);
        if ($valorLimite) {
            $data['valor_limite'] = $valorLimite->valor_limite;
        }

        if ($data['valor_limite_apurado'] != NULL) {
            return $data;
        } else {
            $data['valor_limite_apurado'] = 0;
            $data['valor_saldo'] = $data['valor_limite'];
            return $data;
        }
    }

    public function getUnidades($prolic_destino)
    {
        $sql = "SELECT GROUP_CONCAT(uni_nomeabreviado SEPARATOR ', ') AS uni_nomeabreviado FROM unidade_uni WHERE uni_codunidade IN($prolic_destino)";
        $return = Unidades::findBySQL($sql)->one();
        return $return['uni_nomeabreviado'];
    }

    //FUNÇÃO PARA COVERTER NUMERO POR EXTENSO
    public function removerFormatacaoNumero($strNumero)
    {
        $strNumero = trim(str_replace("R$", '', $strNumero));
        $vetVirgula = explode(",", $strNumero);
        if (count($vetVirgula) == 1) {
            $acentos = array(".");
            $resultado = str_replace($acentos, "", $strNumero);
            return $resultado;
        } else if (count($vetVirgula) != 2) {
            return $strNumero;
        }
        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr($vetVirgula[1], 0, 2);
        $acentos = array(".");
        $resultado = str_replace($acentos, "", $strNumero);
        $resultado = $resultado . "." . $strDecimal;
        return $resultado;
    }
    public  function converte($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
    {
        $valor = self::removerFormatacaoNumero($valor);
        $singular = null;
        $plural = null;
        if ($bolExibirMoeda) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        } else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
        if ($bolPalavraFeminina) {
            if ($valor == 1)
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            else
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }
        $z = 0;
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];
        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }
        $rt = mb_substr($rt, 1);
        return ($rt ? trim($rt) : "zero");
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'prolic_codprocesso' => 'Nº Processo',
            'prolic_dataprocesso' => 'Data Processo',
            'ano_id' => 'Ano',
            'prolic_objeto' => 'Objeto',
            'prolic_codmxm' => 'Requisição MXM',
            'prolic_destino' => 'Destino',
            'modalidade_valorlimite_id' => 'Ramo',
            'prolic_sequenciamodal' => 'Nº Mod',
            'artigo_id' => 'Artigo',
            'prolic_cotacoes' => 'Cotações',
            'prolic_centrocusto' => 'Centro de Custo',
            'prolic_elementodespesa' => 'Elemento Despesa',
            'prolic_valorestimado' => 'Valor Estimado',
            'prolic_valoraditivo' => 'Valor Aditivo',
            'prolic_valorefetivo' => 'Valor Efetivo',
            'recursos_id' => 'Recursos',
            'comprador_id' => 'Comprador',
            'prolic_datacertame' => 'Data Certame',
            'prolic_datadevolucao' => 'Data Devolução',
            'situacao_id' => 'Situação',
            'prolic_datahomologacao' => 'Data Homologação',
            'prolic_motivo' => 'Motivo',
            'prolic_usuariocriacao' => 'Usuario Criação',
            'prolic_datacriacao' => 'Data Criação',
            'prolic_usuarioatualizacao' => 'Usuario Atualização',
            'prolic_dataatualizacao' => 'Data Atualização',
            'prolic_empresa' => 'Empresa',
            'modalidade' => 'Modalidade',
            'ramo' => 'Ramo',
            'valor_limite_hidden' => 'Valor Limite',
            'valor_limite_apurado' => 'Valor Apurado',
            'valor_limite_apurado_hidden' => 'Valor Apurado',
            'prolic_valorefetivo_hidden' => 'Valor Estimado',
            'valor_saldo_hidden' => 'Saldo',
            'ciclototal' => 'Ciclo Total',
            'ciclocertame' => 'Ciclo Certame',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObservacoes()
    {
        return $this->hasMany(Observacoes::className(), ['processo_licitatorio_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAno()
    {
        return $this->hasOne(Ano::className(), ['id' => 'ano_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtigo()
    {
        return $this->hasOne(Artigo::className(), ['id' => 'artigo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComprador()
    {
        return $this->hasOne(Comprador::className(), ['id' => 'comprador_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresa::className(), ['id' => 'empresa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModalidadeValorlimite()
    {
        return $this->hasOne(ModalidadeValorlimite::className(), ['id' => 'modalidade_valorlimite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecursos()
    {
        return $this->hasOne(Recursos::className(), ['id' => 'recursos_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSituacao()
    {
        return $this->hasOne(Situacao::className(), ['id' => 'situacao_id']);
    }
}

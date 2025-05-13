<?php

namespace app\models\base;

use app\models\processolicitatorio\ProcessoLicitatorio;
use Yii;

/**
 * This is the model class for table "modalidade_valorlimite".
 *
 * @property int $id
 * @property int $modalidade_id
 * @property int $ramo_id
 * @property int $ano_id
 * @property double $valor_limite
 * @property int $status
 * @property string $homologacao_usuario
 * @property string $homologacao_data
 * @property string $tipo_modalidade
 *
 * @property Ano $ano
 * @property Modalidade $modalidade
 * @property Ramo $ramo
 * @property ProcessoLicitatorio[] $processoLicitatorios
 */
class ModalidadeValorlimite extends \yii\db\ActiveRecord
{
    public $valor_limite_apurado;
    public $valor_saldo;
    public $valor_limite_fake;

    const MOD_CONVITE = 2;
    const MOD_CONCORRENCIA = 4;
    const MOD_LEILAO = 5;

    const TIPO_OBRAS_SERVICOS = 'Obras e serviços de engenharia';
    const TIPO_COMPRAS = 'Compras e demais serviços';
    const TIPO_ALIENACOES = 'Alienações de bens, sempre precedidas de avaliação';

    const LIMITE_OBRAS_CONVITE = 2465000.00;
    const LIMITE_COMPRAS_CONVITE = 826000.00;
    const LIMITE_ALIENACOES_CONCORRENCIA = 92000.00;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modalidade_valorlimite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modalidade_id', 'ramo_id', 'ano_id', 'valor_limite', 'status', 'tipo_modalidade'], 'required'],
            [['tipo_modalidade', 'modalidade_id', 'ramo_id', 'ano_id'], 'validarCombinacaoUnica'],
            [['modalidade_id', 'ramo_id', 'ano_id', 'status'], 'integer'],
            [['valor_limite'], 'number'],
            [['homologacao_data'], 'safe'],
            [['homologacao_usuario', 'tipo_modalidade'], 'string', 'max' => 255],
            [['ano_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ano::className(), 'targetAttribute' => ['ano_id' => 'id']],
            [['modalidade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidade::className(), 'targetAttribute' => ['modalidade_id' => 'id']],
            [['ramo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ramo::className(), 'targetAttribute' => ['ramo_id' => 'id']],
        ];
    }

    //Replace de ',' por '.' nos valores
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->valor_limite = str_replace(",", ".", $this->valor_limite);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'modalidade_id' => 'Modalidade',
            'ramo_id' => 'Segmento',
            'ano_id' => 'Ano',
            'valor_limite' => 'Valor Limite',
            'status' => 'Status',
            'homologacao_usuario' => 'Homologado Por',
            'homologacao_data' => 'Data Homologação',
            'tipo_modalidade' => 'Tipo de Modalidade',
        ];
    }

    public function validarCombinacaoUnica($attribute, $params)
    {
        $query = self::find()->where([
            'tipo_modalidade' => $this->tipo_modalidade,
            'modalidade_id'   => $this->modalidade_id,
            'ramo_id'         => $this->ramo_id,
            'ano_id'          => $this->ano_id,
        ]);

        // Se for edição, ignora o próprio registro
        if (!$this->isNewRecord) {
            $query->andWhere(['<>', 'id', $this->id]);
        }

        if ($query->exists()) {
            $this->addError('tipo_modalidade', 'Já existe um valor limite cadastrado com essa combinação de Tipo de Modalidade, Modalidade, Segmento e Ano.');
        }
    }

    public function getValorApurado()
    {
        return (float) \app\models\processolicitatorio\ProcessoLicitatorio::find()
            ->where(['modalidade_valorlimite_id' => $this->id])
            ->select(['soma' => new \yii\db\Expression('SUM(IFNULL(prolic_valorestimado, 0) + IFNULL(prolic_valoraditivo, 0))')])
            ->scalar();
    }

    public static function getTiposModalidade()
    {
        return [
            self::TIPO_OBRAS_SERVICOS => self::TIPO_OBRAS_SERVICOS,
            self::TIPO_COMPRAS => self::TIPO_COMPRAS,
            self::TIPO_ALIENACOES => self::TIPO_ALIENACOES,
        ];
    }

    public function verificarTipoModalidade()
    {
        switch ($this->tipo_modalidade) {
            case self::TIPO_OBRAS_SERVICOS:
                return $this->valor_limite <= self::LIMITE_OBRAS_CONVITE ? 'CONVITE' : 'CONCORRÊNCIA';

            case self::TIPO_COMPRAS:
                return $this->valor_limite <= self::LIMITE_COMPRAS_CONVITE ? 'CONVITE' : 'CONCORRÊNCIA';

            case self::TIPO_ALIENACOES:
                return $this->valor_limite > self::LIMITE_ALIENACOES_CONCORRENCIA ? 'LEILÃO OU CONCORRÊNCIA' : 'DISPENSÁVEL';

            default:
                return null;
        }
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
    public function getModalidade()
    {
        return $this->hasOne(Modalidade::className(), ['id' => 'modalidade_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRamo()
    {
        return $this->hasOne(Ramo::className(), ['id' => 'ramo_id']);
    }

    public function getProcessos()
    {
        return $this->hasMany(ProcessoLicitatorio::class, ['modalidade_valorlimite_id' => 'id']);
    }
}

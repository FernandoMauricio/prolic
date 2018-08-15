<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "centrocusto_cen".
 *
 * @property string $cen_codcentrocusto
 * @property string $cen_centrocusto
 * @property string $cen_nomecentrocusto
 * @property string $cen_coddepartamento
 * @property string $cen_codsituacao
 * @property string $cen_codunidade
 * @property int $cen_codsegmento
 * @property int $cen_codtipoacao
 * @property string $cen_nomesegmento
 * @property string $cen_nometipoacao
 * @property string $cen_codano
 * @property string $cen_centrocustoreduzido
 * @property string $cen_usuario
 * @property string $cen_data
 *
 * @property AnocentrocustoAnce $cenCodano
 * @property DepartamentoDep $cenCoddepartamento
 * @property SituacaosistemaSitsis $cenCodsituacao
 * @property UnidadeUni $cenCodunidade
 */
class Centrocusto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'centrocusto_cen';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_base');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cen_centrocusto', 'cen_codsituacao', 'cen_codunidade', 'cen_codano'], 'required'],
            [['cen_coddepartamento', 'cen_codsituacao', 'cen_codunidade', 'cen_codsegmento', 'cen_codtipoacao', 'cen_codano'], 'integer'],
            [['cen_data'], 'safe'],
            [['cen_centrocusto', 'cen_nomesegmento', 'cen_nometipoacao'], 'string', 'max' => 45],
            [['cen_nomecentrocusto', 'cen_usuario'], 'string', 'max' => 100],
            [['cen_centrocustoreduzido'], 'string', 'max' => 10],
            [['cen_codano'], 'exist', 'skipOnError' => true, 'targetClass' => AnocentrocustoAnce::className(), 'targetAttribute' => ['cen_codano' => 'ance_coddocano']],
            [['cen_coddepartamento'], 'exist', 'skipOnError' => true, 'targetClass' => DepartamentoDep::className(), 'targetAttribute' => ['cen_coddepartamento' => 'dep_coddepartamento']],
            [['cen_codsituacao'], 'exist', 'skipOnError' => true, 'targetClass' => SituacaosistemaSitsis::className(), 'targetAttribute' => ['cen_codsituacao' => 'sitsis_codsituacao']],
            [['cen_codunidade'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadeUni::className(), 'targetAttribute' => ['cen_codunidade' => 'uni_codunidade']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cen_codcentrocusto' => 'Cen Codcentrocusto',
            'cen_centrocusto' => 'Cen Centrocusto',
            'cen_nomecentrocusto' => 'Cen Nomecentrocusto',
            'cen_coddepartamento' => 'Cen Coddepartamento',
            'cen_codsituacao' => 'Cen Codsituacao',
            'cen_codunidade' => 'Cen Codunidade',
            'cen_codsegmento' => 'Cen Codsegmento',
            'cen_codtipoacao' => 'Cen Codtipoacao',
            'cen_nomesegmento' => 'Cen Nomesegmento',
            'cen_nometipoacao' => 'Cen Nometipoacao',
            'cen_codano' => 'Cen Codano',
            'cen_centrocustoreduzido' => 'Cen Centrocustoreduzido',
            'cen_usuario' => 'Cen Usuario',
            'cen_data' => 'Cen Data',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCenCodano()
    {
        return $this->hasOne(AnocentrocustoAnce::className(), ['ance_coddocano' => 'cen_codano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCenCoddepartamento()
    {
        return $this->hasOne(DepartamentoDep::className(), ['dep_coddepartamento' => 'cen_coddepartamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCenCodsituacao()
    {
        return $this->hasOne(SituacaosistemaSitsis::className(), ['sitsis_codsituacao' => 'cen_codsituacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCenCodunidade()
    {
        return $this->hasOne(UnidadeUni::className(), ['uni_codunidade' => 'cen_codunidade']);
    }
}

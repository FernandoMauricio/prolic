<?php

namespace app\models\processolicitatorio;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\processolicitatorio\ProcessoLicitatorio;

/**
 * ProcessoLicitatorioSearch represents the model behind the search form of `app\models\processolicitatorio\ProcessoLicitatorio`.
 */
class ProcessoLicitatorioSearch extends ProcessoLicitatorio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ano', 'prolic_codmxm', 'modalidade_valorlimite_id', 'prolic_sequenciamodal', 'artigo_id', 'prolic_cotacoes', 'recursos_id', 'situacao_id', 'prolic_codprocesso'], 'integer'],
            [['prolic_objeto', 'prolic_destino', 'prolic_centrocusto', 'prolic_elementodespesa', 'prolic_dataprocesso', 'prolic_datacertame', 'prolic_datadevolucao', 'prolic_datahomologacao', 'prolic_motivo', 'prolic_usuariocriacao', 'prolic_datacriacao', 'prolic_usuarioatualizacao', 'prolic_dataatualizacao', 'modalidade', 'comprador_id', 'prolic_empresa'], 'safe'],
            [['prolic_valorestimado', 'prolic_valorefetivo'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ProcessoLicitatorio::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith(['modalidadeValorlimite.modalidade', 'comprador']);

        $dataProvider->sort->attributes['modalidade'] = [
            'asc' => ['modalidade.mod_descricao' => SORT_ASC],
            'desc' => ['modalidade.mod_descricao' => SORT_DESC],
            'label' => 'Modalidade',
        ];

        if (!empty($this->prolic_dataprocesso)) {
            $d = \DateTime::createFromFormat('d/m/Y', $this->prolic_dataprocesso);
            if ($d) {
                $query->andFilterWhere([
                    'prolic_dataprocesso' => $d->format('Y-m-d'),
                ]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'processo_licitatorio.id' => $this->id,
            'prolic_codprocesso' => $this->prolic_codprocesso,
            'prolic_codmxm' => $this->prolic_codmxm,
            'modalidade_valorlimite_id' => $this->modalidade_valorlimite_id,
            'prolic_sequenciamodal' => $this->prolic_sequenciamodal,
            'artigo_id' => $this->artigo_id,
            'prolic_cotacoes' => $this->prolic_cotacoes,
            'prolic_valorestimado' => $this->prolic_valorestimado,
            'prolic_valorefetivo' => $this->prolic_valorefetivo,
            'recursos_id' => $this->recursos_id,
            'prolic_datacertame' => $this->prolic_datacertame,
            'prolic_datadevolucao' => $this->prolic_datadevolucao,
            'situacao_id' => $this->situacao_id,
            'prolic_datahomologacao' => $this->prolic_datahomologacao,
            'prolic_datacriacao' => $this->prolic_datacriacao,
            'prolic_dataatualizacao' => $this->prolic_dataatualizacao,
        ]);

        $query->andFilterWhere(['like', 'prolic_objeto', $this->prolic_objeto])
            ->andFilterWhere(['like', 'modalidade_valorlimite.modalidade_id', $this->modalidade])
            ->andFilterWhere(['like', 'prolic_destino', $this->prolic_destino])
            ->andFilterWhere(['like', 'prolic_centrocusto', $this->prolic_centrocusto])
            ->andFilterWhere(['like', 'prolic_elementodespesa', $this->prolic_elementodespesa])
            ->andFilterWhere(['like', 'prolic_motivo', $this->prolic_motivo])
            ->andFilterWhere(['like', 'prolic_usuariocriacao', $this->prolic_usuariocriacao])
            ->andFilterWhere(['like', 'prolic_usuarioatualizacao', $this->prolic_usuarioatualizacao])
            ->andFilterWhere(['like', 'comprador.id', $this->comprador_id])
            ->andFilterWhere(['like', 'processo_licitatorio.ano', $this->ano])
            ->andFilterWhere(['like', 'prolic_empresa', $this->prolic_empresa]);

        // Restringe os resultados se não for admin
        if (!\app\components\helpers\RbacHelper::isAdmin()) {
            $unidadeUsuario = Yii::$app->session->get('sess_codunidade');
            if ($unidadeUsuario) {
                $query->andWhere(['like', 'prolic_destino', $unidadeUsuario]);
            }
        }

        return $dataProvider;
    }
}

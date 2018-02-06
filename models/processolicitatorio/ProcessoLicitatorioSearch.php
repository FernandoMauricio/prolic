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
            [['id', 'ano_id', 'prolic_codmxm', 'modalidade_valorlimite_id', 'prolic_sequenciamodal', 'artigo_id', 'prolic_cotacoes', 'recursos_id', 'situacao_id'], 'integer'],
            [['prolic_objeto', 'prolic_destino', 'prolic_centrocusto', 'prolic_elementodespesa', 'prolic_datacertame', 'prolic_datadevolucao', 'prolic_datahomologacao', 'prolic_motivo', 'prolic_usuariocriacao', 'prolic_datacriacao', 'prolic_usuarioatualizacao', 'prolic_dataatualizacao', 'modalidade', 'comprador_id'], 'safe'],
            [['prolic_valorestimado', 'prolic_valoraditivo', 'prolic_valorefetivo'], 'number'],
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
        ];

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ano_id' => $this->ano_id,
            'prolic_codmxm' => $this->prolic_codmxm,
            'modalidade_valorlimite_id' => $this->modalidade_valorlimite_id,
            'prolic_sequenciamodal' => $this->prolic_sequenciamodal,
            'artigo_id' => $this->artigo_id,
            'prolic_cotacoes' => $this->prolic_cotacoes,
            'prolic_valorestimado' => $this->prolic_valorestimado,
            'prolic_valoraditivo' => $this->prolic_valoraditivo,
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
            ->andFilterWhere(['like', 'comprador.comp_descricao', $this->comprador_id]);

        return $dataProvider;
    }
}

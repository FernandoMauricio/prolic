<?php

namespace app\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\base\ModalidadeValorlimite;

/**
 * ModalidadeValorlimiteSearch represents the model behind the search form of `app\models\base\ModalidadeValorlimite`.
 */
class ModalidadeValorlimiteSearch extends ModalidadeValorlimite
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ano_id', 'status'], 'integer'],
            [['modalidade_id', 'ramo_id', 'homologacao_usuario', 'homologacao_data', 'tipo'], 'safe'],
            [['valor_limite'], 'number'],
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
        $query = ModalidadeValorlimite::find();

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

        $query->joinWith('modalidade')
        ->joinWith('ramo')
        ->joinWith('ano');


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'valor_limite' => $this->valor_limite,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'modalidade.mod_descricao', $this->modalidade_id])
        ->andFilterWhere(['like', 'ramo.ram_descricao', $this->ramo_id])
        ->andFilterWhere(['like', 'ano.an_ano', $this->ano_id])
        ->andFilterWhere(['like', 'homologacao_usuario', $this->homologacao_usuario])
        ->andFilterWhere(['like', 'homologacao_data', $this->homologacao_data])
        ->andFilterWhere(['like', 'tipo', $this->tipo]);
        return $dataProvider;
    }
}

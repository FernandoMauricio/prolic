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
    public $ano_menor_que;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ano', 'status'], 'integer'],
            [['modalidade_id', 'ramo_id', 'homologacao_usuario', 'homologacao_data', 'tipo_modalidade', 'ano_menor_que'], 'safe'],
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
        $query = ModalidadeValorlimite::find()->orderBy(['id' => SORT_DESC]);

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

        if ($this->ano_menor_que) {
            $query->andWhere(['<', 'modalidade_valorlimite.ano', $this->ano_menor_que]);
        }

        $query->joinWith('modalidade')
            ->joinWith('ramo');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'valor_limite' => $this->valor_limite,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'modalidade.id', $this->modalidade_id])
            ->andFilterWhere(['ramo.id' => $this->ramo_id])
            ->andFilterWhere(['modalidade_valorlimite.ano' => $this->ano])
            ->andFilterWhere(['like', 'homologacao_usuario', $this->homologacao_usuario])
            ->andFilterWhere(['like', 'homologacao_data', $this->homologacao_data])
            ->andFilterWhere(['like', 'tipo_modalidade', $this->tipo_modalidade]);
        return $dataProvider;
    }
}

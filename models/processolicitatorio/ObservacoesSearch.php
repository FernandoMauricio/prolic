<?php

namespace app\models\processolicitatorio;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\processolicitatorio\Observacoes;

/**
 * ObservacoesSearch represents the model behind the search form of `app\models\processolicitatorio\Observacoes`.
 */
class ObservacoesSearch extends Observacoes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'processo_licitatorio_id'], 'integer'],
            [['obs_descricao', 'obs_usuariocriacao', 'obs_datacriacao'], 'safe'],
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
        $query = Observacoes::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'obs_datacriacao' => $this->obs_datacriacao,
            'processo_licitatorio_id' => $this->processo_licitatorio_id,
        ]);

        $query->andFilterWhere(['like', 'obs_descricao', $this->obs_descricao])
            ->andFilterWhere(['like', 'obs_usuariocriacao', $this->obs_usuariocriacao]);

        return $dataProvider;
    }
}

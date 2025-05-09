<?php

namespace app\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\base\Comprador;

/**
 * CompradorSearch represents the model behind the search form of `app\models\base\Comprador`.
 */
class CompradorSearch extends Comprador
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'comp_status'], 'integer'],
            [['comp_descricao'], 'safe'],
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
        $query = Comprador::find()->orderBy(['id' => SORT_DESC]);

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
            'comp_status' => $this->comp_status,
        ]);

        $query->andFilterWhere(['like', 'comp_descricao', $this->comp_descricao]);

        return $dataProvider;
    }
}

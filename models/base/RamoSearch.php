<?php

namespace app\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\base\Ramo;

/**
 * RamoSearch represents the model behind the search form of `app\models\base\Ramo`.
 */
class RamoSearch extends Ramo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['ram_descricao', 'ram_status'], 'safe'],
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
        $query = Ramo::find();

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
        ]);

        $query->andFilterWhere(['like', 'ram_descricao', $this->ram_descricao])
            ->andFilterWhere(['like', 'ram_status', $this->ram_status]);

        return $dataProvider;
    }
}

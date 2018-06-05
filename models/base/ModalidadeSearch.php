<?php

namespace app\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\base\Modalidade;

/**
 * ModalidadeSearch represents the model behind the search form of `app\models\base\Modalidade`.
 */
class ModalidadeSearch extends Modalidade
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mod_status'], 'integer'],
            [['mod_descricao'], 'safe'],
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
        $query = Modalidade::find();

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
            'mod_status' => $this->mod_status,
        ]);

        $query->andFilterWhere(['like', 'mod_descricao', $this->mod_descricao]);

        return $dataProvider;
    }
}

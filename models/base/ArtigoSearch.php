<?php

namespace app\models\base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\base\Artigo;

/**
 * ArtigoSearch represents the model behind the search form of `app\models\base\Artigo`.
 */
class ArtigoSearch extends Artigo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'art_status'], 'integer'],
            [['art_tipo', 'art_descricao', 'art_homologacaousuario', 'art_homologacaodata'], 'safe'],
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
        $query = Artigo::find()->orderBy(['id' => SORT_DESC]);

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
            'art_status' => $this->art_status,
        ]);

        $query->andFilterWhere(['like', 'art_descricao', $this->art_descricao])
            ->andFilterWhere(['like', 'art_homologacaousuario', $this->art_homologacaousuario])
            ->andFilterWhere(['like', 'art_homologacaodata', $this->art_homologacaodata])
            ->andFilterWhere(['like', 'art_tipo', $this->art_tipo]);

        return $dataProvider;
    }
}

<?php

namespace app\models\mxm;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReqcompraRcoSearch representa o modelo de busca para `ReqcompraRco`.
 */
class ReqcompraRcoSearch extends ReqcompraRco
{
    public function rules()
    {
        return [
            [['RCO_NUMERO', 'RCO_EMPRESA', 'RCO_TIPO', 'RCO_SETOR', 'RCO_REQUISITANTE', 'RCO_REQCOMPRA', 'RCO_MOEDA'], 'safe'],
            [['RCO_DATA', 'RCO_DTMOV'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // Usa os cenários padrão de Model
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ReqcompraRco::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['RCO_DATA' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1'); // evita retornar todos os dados
            return $dataProvider;
        }

        // Filtros básicos
        $query->andFilterWhere(['like', 'RCO_NUMERO', $this->RCO_NUMERO])
            ->andFilterWhere(['like', 'RCO_EMPRESA', $this->RCO_EMPRESA])
            ->andFilterWhere(['like', 'RCO_TIPO', $this->RCO_TIPO])
            ->andFilterWhere(['like', 'RCO_SETOR', $this->RCO_SETOR])
            ->andFilterWhere(['like', 'RCO_REQUISITANTE', $this->RCO_REQUISITANTE])
            ->andFilterWhere(['like', 'RCO_REQCOMPRA', $this->RCO_REQCOMPRA])
            ->andFilterWhere(['like', 'RCO_MOEDA', $this->RCO_MOEDA]);

        if ($this->RCO_DATA) {
            $query->andWhere(['RCO_DATA' => $this->RCO_DATA]);
        }

        if ($this->RCO_DTMOV) {
            $query->andWhere(['RCO_DTMOV' => $this->RCO_DTMOV]);
        }

        return $dataProvider;
    }
}

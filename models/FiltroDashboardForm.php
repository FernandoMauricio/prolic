<?php

namespace app\models;

use yii\base\Model;

class FiltroDashboardForm extends Model
{
    public $ano;
    public $mes;

    public function rules()
    {
        return [
            [['ano', 'mes'], 'integer'],
        ];
    }
}

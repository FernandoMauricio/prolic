<?php

namespace app\models\processolicitatorio;

use Yii;
use yii\base\Model;


class Capas extends Model
{
    public $cap_tipo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cap_tipo'], 'required'],
            [['cap_tipo'], 'integer'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cap_tipo' => 'Capa',
        ];
    }
}

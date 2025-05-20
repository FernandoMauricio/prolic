<?php

namespace app\models\cache;

use yii\base\Model;

class RequisicaoCache extends Model
{
    public $requisicao = [];
    public $itens = [];

    public function __construct($data = [], $config = [])
    {
        parent::__construct($config);
        $this->requisicao = $data['requisicao'] ?? [];
        $this->itens = $data['itens'] ?? [];
    }

    public function get($campo)
    {
        return $this->requisicao[$campo] ?? null;
    }

    public function getNumero()
    {
        return $this->get('RCO_NUMERO');
    }

    public function getRequisitante()
    {
        return $this->get('RCO_REQUISITANTE');
    }

    public function getDataFormatada()
    {
        return isset($this->requisicao['RCO_DATA'])
            ? \Yii::$app->formatter->asDate($this->requisicao['RCO_DATA'], 'php:d/m/Y')
            : '(não definida)';
    }

    public function getDataMovFormatada()
    {
        return isset($this->requisicao['RCO_DTMOV'])
            ? \Yii::$app->formatter->asDate($this->requisicao['RCO_DTMOV'], 'php:d/m/Y')
            : '(não definida)';
    }

    public function getItens()
    {
        return $this->itens ?? [];
    }
}

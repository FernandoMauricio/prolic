<?php

namespace app\models\cache;

use yii\base\Model;
use app\models\api\WebManagerService;

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

    public function getStatusOnline(): ?string
    {
        $numero = $this->getNumero();
        $status = \app\models\api\WebManagerService::consultarStatusRequisicao($numero);

        \Yii::debug("Status API para {$numero}: " . var_export($status, true), __METHOD__);

        return $status;
    }


    public function getStatusBadge(): string
    {
        $status = $this->getStatusOnline() ?? 'Indisponível';

        $class = 'bg-secondary';
        if (stripos($status, 'aprovado') !== false) {
            $class = 'bg-success';
        } elseif (stripos($status, 'reprovado') !== false) {
            $class = 'bg-danger';
        } elseif (
            stripos($status, 'pendente') !== false ||
            stripos($status, 'aguard') !== false ||
            stripos($status, 'em análise') !== false ||
            stripos($status, 'em aprovação') !== false
        ) {
            $class = 'bg-warning text-dark';
        }

        return \yii\helpers\Html::tag('span', $status, ['class' => "badge $class px-3 py-2"]);
    }

    public function getItens()
    {
        return $this->itens ?? [];
    }
}

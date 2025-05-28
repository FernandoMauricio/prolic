<?php

namespace app\models\cache;

use yii\base\Model;
use app\models\api\WebManagerService;
use yii\bootstrap5\Html;

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

    public function getTipo()
    {
        return $this->requisicao['RCO_TIPO'] ?? '';
    }

    public function getSetor()
    {
        return $this->requisicao['RCO_SETOR'] ?? '';
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

    public function getStatusBadgeHtml(): string
    {
        $status = trim($this->getStatusOnline() ?? 'Indefinido');
        $badgeClass = 'bg-secondary';
        $icon = '<i class="bi bi-question-circle me-1"></i>';

        if (stripos($status, 'aprovado') !== false || stripos($status, 'totalmente atendido') !== false) {
            $badgeClass = 'bg-success';
            $icon = '<i class="bi bi-check-circle-fill me-1"></i>';
        } elseif (stripos($status, 'em aprovação') !== false) {
            $badgeClass = 'bg-warning text-dark';
            $icon = '<i class="bi bi-hourglass-split me-1"></i>';
        } elseif (stripos($status, 'reprovado') !== false) {
            $badgeClass = 'bg-danger';
            $icon = '<i class="bi bi-x-circle-fill me-1"></i>';
        } elseif (stripos($status, 'pendente') !== false || stripos($status, 'aguard') !== false) {
            $badgeClass = 'bg-warning text-dark';
            $icon = '<i class="bi bi-clock-fill me-1"></i>';
        }

        return Html::tag('span', $icon . Html::encode($status), [
            'class' => "badge {$badgeClass} px-2 py-1 ms-3"
        ]);
    }

    public function getItens()
    {
        return $this->itens ?? [];
    }
}

<?php

use yii\helpers\Html;

$status = trim($status ?? '');
$cor = 'bg-secondary text-white';
$icon = '<i class="bi bi-question-circle me-1"></i>';

if (stripos($status, 'aprovado') !== false || stripos($status, 'totalmente atendido') !== false) {
    $cor = 'bg-success';
    $icon = '<i class="bi bi-check-circle-fill me-1"></i>';
} elseif (stripos($status, 'em aprovação') !== false) {
    $cor = 'bg-warning text-dark';
    $icon = '<i class="bi bi-hourglass-split me-1"></i>';
} elseif (stripos($status, 'reprovado') !== false) {
    $cor = 'bg-danger';
    $icon = '<i class="bi bi-x-circle-fill me-1"></i>';
} elseif (stripos($status, 'pendente') !== false || stripos($status, 'aguard') !== false) {
    $cor = 'bg-warning text-dark';
    $icon = '<i class="bi bi-clock-fill me-1"></i>';
}

echo Html::tag('span', $icon . Html::encode($status), ['class' => "badge $cor px-2 py-1"]);

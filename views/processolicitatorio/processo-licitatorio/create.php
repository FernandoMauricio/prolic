<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\ProcessoLicitatorio */

?>
<div class="processo-licitatorio-create">

    <?= $this->render('gerar-processo-licitatorio', [
        'model' => $model,
        'ano' => $ano,
        'ramo' => $ramo,
        'destinos' => $destinos,
        'valorlimite' => $valorlimite,
        'artigo' => $artigo,
        'centrocusto' => $centrocusto,
        'recurso' => $recurso,
        'comprador' => $comprador,
        'situacao' => $situacao,
        'empresa' => $empresa,
    ]) ?>

</div>

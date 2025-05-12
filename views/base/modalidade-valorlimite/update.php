<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */

$this->title = 'Atualizar Valor Limite: ' . $model->id . '';
$this->params['breadcrumbs'][] = ['label' => 'Parâmetros do Sistema', 'url' => ['/site/parametros']];
$this->params['breadcrumbs'][] = ['label' => 'Valor Limite - Modalidade', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="modalidade-valorlimite-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modalidade' => $modalidade,
        'ano' => $ano,
        'ramo' => $ramo,
    ]) ?>

</div>
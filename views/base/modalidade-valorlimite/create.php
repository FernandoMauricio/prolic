<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */

$this->title = 'Novo Valor Limite';
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Valor Limite - Modalidade', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidade-valorlimite-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modalidade' => $modalidade,
        'ano' => $ano,
        'ramo' => $ramo,
    ]) ?>

</div>

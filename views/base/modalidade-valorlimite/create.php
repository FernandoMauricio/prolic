<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */

$this->title = 'Create Modalidade Valorlimite';
$this->params['breadcrumbs'][] = ['label' => 'Modalidade Valorlimites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modalidade-valorlimite-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

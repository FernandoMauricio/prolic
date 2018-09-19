<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Modalidade */

$this->title = 'Atualizar Modalidade:'.$model->id.'';
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Modalidades', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="modalidade-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

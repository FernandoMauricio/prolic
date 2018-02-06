<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\base\Empresa */

$this->title = 'Atualizar Empresa: '.$model->emp_descricao.'';
$this->params['breadcrumbs'][] = ['label' => 'Listagem de Empresas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="empresa-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

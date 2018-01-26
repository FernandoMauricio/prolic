<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\base\Ramo */

$this->title = 'Create Ramo';
$this->params['breadcrumbs'][] = ['label' => 'Ramos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ramo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

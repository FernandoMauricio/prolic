<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\processolicitatorio\Observacoes */

?>
<div class="observacoes-create">

    <?= $this->render('_form', [
        'model' => $model,
        'processolicitatorio' => $processolicitatorio,
    ]) ?>

</div>

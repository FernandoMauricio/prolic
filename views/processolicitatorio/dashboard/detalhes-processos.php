<?php

use yii\helpers\Html;

/** @var array $processos */

?>

<ul class="list-group">
    <?php foreach ($processos as $p): ?>
        <li class="list-group-item">
            <strong><?= Html::encode($p->prolic_codprocesso) ?></strong><br>
            <?= Html::encode($p->prolic_objeto) ?>
        </li>
    <?php endforeach; ?>
</ul>
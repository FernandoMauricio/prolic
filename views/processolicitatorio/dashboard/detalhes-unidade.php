<?php

use yii\helpers\Html;

/** @var string $nome */
/** @var app\models\processolicitatorio\ProcessoLicitatorio[] $processos */
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h4>Unidade: <?= Html::encode($nome) ?></h4>
    </div>
    <div class="panel-body">
        <?php if (!empty($processos)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Processo</th>
                            <th>Modalidade</th>
                            <th>Segmento</th>
                            <th>Recurso</th>
                            <th>Objeto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processos as $p): ?>
                            <tr>
                                <td><?= Html::encode($p->prolic_codprocesso) ?></td>
                                <td><?= Html::encode($p->modalidadeValorlimite->modalidade->mod_descricao ?? '-') ?></td>
                                <td><?= Html::encode($p->modalidadeValorlimite->ramo->ram_descricao ?? '-') ?></td>
                                <td><?= Html::encode($p->recursos->rec_descricao ?? '-') ?></td>
                                <td><?= Html::encode($p->prolic_objeto) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-danger">Nenhum processo encontrado para esta unidade.</p>
        <?php endif; ?>
    </div>
</div>
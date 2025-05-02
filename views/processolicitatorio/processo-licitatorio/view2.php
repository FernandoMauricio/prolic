<style>
    /* Melhorar espaçamento dos botões */
    .action-buttons {
        margin-bottom: 30px;
    }

    /* Linhas suaves entre os itens de painel */
    .panel-body p {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
        margin: 0;
    }

    /* Retirar borda do último item */
    .panel-body p:last-child {
        border-bottom: none;
    }

    /* Table no painel de Observações */
    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    /* Animação de fade com leve deslizamento */
    @keyframes fadeSlideIn {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Aplicar animação nos painéis */
    .panel {
        animation: fadeSlideIn 0.8s ease-out;
        transition: box-shadow 0.6s ease;
        border-radius: 6px;
    }

    /* Adiciona leve sombra ao passar o mouse */
    .panel:hover {
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
</style>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;

/* @var $this yii\web\View */
/* @var $model \app\models\processolicitatorio\ProcessoLicitatorio */

$this->title = 'Processo ' . Html::encode($model->prolic_codprocesso);
$this->params['breadcrumbs'][] = ['label' => 'Processos Licitatórios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="processo-licitatorio-view">

    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>

    <!-- Botões com espaçamento adequado -->
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-arrow-left"></i> Voltar', ['index'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Incluir Observação', [
            'value' => Url::to(['processolicitatorio/processo-licitatorio/observacoes', 'id' => $model->id]),
            'class' => 'btn btn-success',
            'id' => 'modalButton'
        ]) ?>
        <?= Html::button('<i class="glyphicon glyphicon-print"></i> Gerar Capa', [
            'value' => Url::to(['processolicitatorio/capas/gerar-relatorio', 'id' => $model->id]),
            'class' => 'btn btn-warning pull-right',
            'id' => 'modalButton2'
        ]) ?>
    </div>

    <?php
    Modal::begin([
        'title' => '<h3>Incluir Observação</h3>',
        'id' => 'modal',
        'size' => 'modal-lg',
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();

    Modal::begin([
        'title' => '<h3>Imprimir Capa</h3>',
        'id' => 'modal2',
        'size' => 'modal-lg',
    ]);
    echo "<div id='modalContent2'></div>";
    Modal::end();
    ?>

    <div class="row">
        <!-- Dados Principais -->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-file"></i> Dados do Processo</strong></div>
                <div class="panel-body">
                    <p><strong>Ano:</strong> <?= Html::encode($model->ano->an_ano) ?></p>
                    <p><strong>Código MXM:</strong> <?= Html::encode($model->prolic_codmxm) ?></p>
                    <p><strong>Sequência:</strong> <?= Html::encode($model->prolic_sequenciamodal . '/' . $model->ano->an_ano) ?></p>
                    <p><strong>Modalidade:</strong> <?= Html::encode($model->modalidadeValorlimite->modalidade->mod_descricao) ?></p>
                    <p><strong>Ramo:</strong> <?= Html::encode($model->modalidadeValorlimite->ramo->ram_descricao) ?></p>
                    <p><strong>Situação:</strong> <?= Html::encode($model->situacao->sit_descricao) ?></p>
                </div>
            </div>
        </div>

        <!-- Objeto e Destino -->
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-flag"></i> Objeto</strong></div>
                <div class="panel-body">
                    <p><?= nl2br(Html::encode($model->prolic_objeto)) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-map-marker"></i> Destino</strong></div>
                <div class="panel-body">
                    <p><?= nl2br(Html::encode(\app\models\processolicitatorio\ProcessoLicitatorio::getUnidades($model->prolic_destino))) ?></p>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <!-- Informações Financeiras -->
        <div class="col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-usd"></i> Informações Financeiras</strong></div>
                <div class="panel-body">
                    <p><strong>Valor Estimado:</strong> R$ <?= number_format($model->prolic_valorestimado, 2, ',', '.') ?></p>
                    <p><strong>Valor Aditivo:</strong> R$ <?= number_format($model->prolic_valoraditivo, 2, ',', '.') ?></p>
                    <p><strong>Valor Efetivo:</strong> R$ <?= number_format($model->prolic_valorefetivo, 2, ',', '.') ?></p>
                </div>
            </div>
        </div>

        <!-- Observações -->
        <div class="col-md-6">
            <div class="panel panel-danger">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-comment"></i> Observações</strong></div>
                <div class="panel-body">
                    <?php if ($model->observacoes): ?>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Usuário</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($model->observacoes as $obs): ?>
                                    <tr>
                                        <td><?= Html::encode($obs->obs_descricao) ?></td>
                                        <td><?= Html::encode($obs->obs_usuariocriacao) ?></td>
                                        <td><?= Yii::$app->formatter->asDate($obs->obs_datacriacao) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-muted">Nenhuma observação cadastrada para este processo.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <!-- Datas Importantes -->
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-calendar"></i> Datas</strong></div>
                <div class="panel-body">
                    <p><strong>Certame:</strong> <?= Yii::$app->formatter->asDate($model->prolic_datacertame) ?></p>
                    <p><strong>Devolução:</strong> <?= Yii::$app->formatter->asDate($model->prolic_datadevolucao) ?></p>
                    <p><strong>Homologação:</strong> <?= Yii::$app->formatter->asDate($model->prolic_datahomologacao) ?></p>
                </div>
            </div>
        </div>

        <!-- Auditoria -->
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><i class="glyphicon glyphicon-eye-open"></i> Auditoria</strong></div>
                <div class="panel-body">
                    <p><strong>Criado por:</strong> <?= Html::encode($model->prolic_usuariocriacao) ?></p>
                    <p><strong>Data Criação:</strong> <?= Yii::$app->formatter->asDate($model->prolic_datacriacao) ?></p>
                    <p><strong>Atualizado por:</strong> <?= Html::encode($model->prolic_usuarioatualizacao) ?></p>
                    <p><strong>Data Atualização:</strong> <?= Yii::$app->formatter->asDate($model->prolic_dataatualizacao) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    $('#modalButton').click(function () {
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
    });
    $('#modalButton2').click(function () {
        $('#modal2').modal('show')
            .find('#modalContent2')
            .load($(this).attr('value'));
    });
});
JS;
$this->registerJs($script);
?>
<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\base\ModalidadeValorlimite */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="modalidade-valorlimite-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card shadow-sm mb-4 border-primary bg-light">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-cash-coin me-2"></i> Cadastro de Valor Limite
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <!-- Coluna do formulário -->
                <div class="col-lg-7">
                    <!-- Seção: Definição da Modalidade -->
                    <div class="card border-0 bg-white mb-4">
                        <div class="card-header bg-soft-primary">
                            <strong><i class="bi bi-bookmark-check me-2"></i> Definição da Modalidade</strong>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <?= $form->field($model, 'tipo_modalidade')->widget(Select2::class, [
                                        'data' => \app\models\base\ModalidadeValorlimite::getTiposModalidade(),
                                        'options' => ['placeholder' => 'Selecione o Tipo...'],
                                        'pluginOptions' => ['allowClear' => true],
                                    ]) ?>
                                </div>
                                <div class="col-md-5">
                                    <?= $form->field($model, 'modalidade_id')->widget(Select2::class, [
                                        'data' => ArrayHelper::map($modalidade, 'id', 'mod_descricao'),
                                        'options' => ['placeholder' => 'Selecione a Modalidade...'],
                                        'pluginOptions' => ['allowClear' => true],
                                        'disabled' => !$model->isNewRecord,
                                    ]) ?>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'ramo_id')->widget(Select2::class, [
                                        'data' => ArrayHelper::map($ramo, 'id', 'ram_descricao'),
                                        'options' => ['placeholder' => 'Selecione o Segmento...'],
                                        'pluginOptions' => ['allowClear' => true],
                                        'disabled' => !$model->isNewRecord,
                                    ]) ?>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'valor_limite')->hiddenInput()->label(false) ?>

                                    <div id="card-valor-definido" class="card bg-success bg-opacity-25 border-0 d-none">
                                        <div class="card-body py-2 px-3 d-flex align-items-center">
                                            <i class="bi bi-cash-coin fs-4 text-success me-2"></i>
                                            <div>
                                                <div class="fw-bold text-success small">Valor Limite Aplicado</div>
                                                <div class="fs-5 fw-bold text-success" id="valor-formatado">R$ 0,00</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="card-sem-limite" class="card bg-warning bg-opacity-25 border-0 mt-2 d-none">
                                        <div class="card-body py-2 px-3 d-flex align-items-center">
                                            <i class="bi bi-exclamation-triangle-fill fs-4 text-warning me-2"></i>
                                            <div class="text-warning-emphasis">
                                                <div class="fw-bold small">Sem limite definido para essa modalidade</div>
                                                <div class="small fst-italic">Valores acima do teto legal — aplicável somente em CONCORRÊNCIA</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="text-end mt-3">
                                <?= Html::a('<i class="bi bi-arrow-left-circle me-1"></i> Cancelar', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
                                <?= Html::submitButton('<i class="bi bi-check-circle-fill me-1"></i> Gravar', ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coluna com os blocos explicativos -->
                <div class="col-lg-5">
                    <!-- Bloco explicativo — Resolução 007/2025 -->
                    <div class="alert alert-warning border-start border-4 border-warning shadow-sm mb-4" role="alert">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-bold text-warning mb-0">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Art. 5.º — Instâncias autorizadoras de despesas
                            </h6>
                            <a href="/prolic/web/uploads/Resolucao_007_2025.pdf" target="_blank" class="btn btn-sm btn-outline-warning ms-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Resolução 007/2025
                            </a>
                        </div>
                        <ul class="mb-3 small ps-3">
                            <li><strong>I - Direção da Divisão Administrativa:</strong> até <strong>R$ 92.000,00</strong></li>
                            <li><strong>II - Direção Regional:</strong> de <strong>R$ 92.000,01</strong> até <strong>R$ 826.000,00</strong></li>
                            <li><strong>III - Presidência do Conselho Regional:</strong> a partir de <strong>R$ 826.000,01</strong></li>
                        </ul>
                    </div>

                    <!-- Bloco explicativo — Resolução 1270/2024 -->
                    <div class="alert alert-info border-start border-4 border-primary shadow-sm" role="alert">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-bold text-primary mb-0">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                Art. 6.º e 7.º — Limites legais por tipo de modalidade
                            </h6>
                            <a href="/prolic/web/uploads/Resolucao_1270_2024.pdf" target="_blank" class="btn btn-sm btn-outline-primary ms-3">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Resolução 1270/2024
                            </a>
                        </div>

                        <p class="small text-muted mb-2"><strong>Art. 6.º</strong> — Para as modalidades licitatórias, aplicam-se os limites legais:</p>
                        <ul class="mb-3 small ps-3">
                            <li><strong>I - Obras e serviços de engenharia:</strong>
                                <ul class="mb-2">
                                    <li>CONVITE: até <strong>R$ 2.465.000,00</strong></li>
                                    <li>CONCORRÊNCIA: acima de <strong>R$ 2.465.000,00</strong></li>
                                </ul>
                            </li>
                            <li><strong>II - Compras e demais serviços:</strong>
                                <ul class="mb-2">
                                    <li>CONVITE: até <strong>R$ 826.000,00</strong></li>
                                    <li>CONCORRÊNCIA: acima de <strong>R$ 826.000,00</strong></li>
                                </ul>
                            </li>
                            <li><strong>III - Alienações de bens:</strong>
                                <ul class="mb-0">
                                    <li>LEILÃO ou CONCORRÊNCIA: acima de <strong>R$ 92.000,00</strong>, dispensável na fase de habilitação</li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>


<?php

use app\models\base\ModalidadeValorlimite;

$tipoObras = ModalidadeValorlimite::TIPO_OBRAS_SERVICOS;
$tipoCompras = ModalidadeValorlimite::TIPO_COMPRAS;
$tipoAlienacoes = ModalidadeValorlimite::TIPO_ALIENACOES;

$modConvite = ModalidadeValorlimite::MOD_CONVITE;
$modConcorrencia = ModalidadeValorlimite::MOD_CONCORRENCIA;
$modLeilao = ModalidadeValorlimite::MOD_LEILAO;

$limiteObras = ModalidadeValorlimite::LIMITE_OBRAS_CONVITE;
$limiteCompras = ModalidadeValorlimite::LIMITE_COMPRAS_CONVITE;
$limiteAlienacoes = ModalidadeValorlimite::LIMITE_ALIENACOES_CONCORRENCIA;

?>

<?php
$this->registerJs(<<<JS
function atualizarValorLimite() {
    const tipo = $('#modalidadevalorlimite-tipo_modalidade').val();
    const modalidade = $('#modalidadevalorlimite-modalidade_id').val();

    const campoOculto = $('#modalidadevalorlimite-valor_limite');
    const cardDefinido = $('#card-valor-definido');
    const cardSemLimite = $('#card-sem-limite');
    const spanFormatado = $('#valor-formatado');

    let valor = null;
    let readonly = false;

    if (tipo === '{$tipoObras}') {
        if (modalidade == {$modConvite}) {
            valor = {$limiteObras};
        } else if (modalidade == {$modConcorrencia}) {
            readonly = true;
        }
    }

    if (tipo === '{$tipoCompras}') {
        if (modalidade == {$modConvite}) {
            valor = {$limiteCompras};
        } else if (modalidade == {$modConcorrencia}) {
            readonly = true;
        }
    }

    if (tipo === '{$tipoAlienacoes}') {
    if (modalidade == {$modLeilao} || modalidade == {$modConcorrencia}) {
        readonly = true; 
    }
}


    // fallback default
    if (valor === null && !readonly) {
        valor = 92000.00;
    }

    if (readonly) {
        // Para modalidades ilimitadas (CONCORRÊNCIA ou LEILÃO em Alienações):
        campoOculto.val(999999999.99);
        cardDefinido.addClass('d-none');
        cardSemLimite.removeClass('d-none');
        spanFormatado.text('');
    } else {
        const formatted = valor.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        campoOculto.val(valor);
        spanFormatado.text('R$ ' + formatted);
        cardSemLimite.addClass('d-none');
        cardDefinido.removeClass('d-none');
    }
}


$('#modalidadevalorlimite-tipo_modalidade, #modalidadevalorlimite-modalidade_id').on('change', atualizarValorLimite);
JS);
?>
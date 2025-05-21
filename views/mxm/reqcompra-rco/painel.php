<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ArrayDataProvider $dataProvider */

$this->title = 'Painel de Requisições';
$this->context->layout = 'blank';

$this->registerCss('body { background-color: #f9f9f9; font-size: 1.3rem; overflow: hidden; }');
?>

<div class="container-fluid py-4">
    <h1 class="text-primary text-center mb-4 fw-bold">
        <i class="bi bi-display"></i> Painel de Requisições - Atualização Automática
    </h1>

    <?php Pjax::begin(['id' => 'painel-requisicoes', 'timeout' => 5000, 'enablePushState' => false]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            [
                'attribute' => 'RCO_NUMERO',
                'label' => 'Número',
                'value' => fn($model) => $model->getNumero(),
                'contentOptions' => ['class' => 'fw-bold text-primary']
            ],
            [
                'label' => 'Data',
                'value' => fn($model) => $model->getDataFormatada(),
            ],
            [
                'label' => 'Setor',
                'value' => fn($model) => $model->get('RCO_SETOR'),
            ],
            [
                'label' => 'Requisitante',
                'value' => fn($model) => $model->getRequisitante(),
            ],
            [
                'label' => 'Status (API)',
                'format' => 'raw',
                'value' => fn($model) => Html::tag('span', 'Carregando...', [
                    'class' => 'badge bg-secondary px-3 py-2 requisicao-status',
                    'data-numero' => $model->getNumero(),
                ]),
                'contentOptions' => ['class' => 'text-center']
            ],
        ],
        'tableOptions' => ['class' => 'table table-bordered table-striped table-hover text-center align-middle'],
        'headerRowOptions' => ['class' => 'table-light'],
        'rowOptions' => ['style' => 'height: 80px;'],
        'hover' => true,
        'condensed' => false,
        'responsiveWrap' => false,
    ]) ?>
    <?php Pjax::end(); ?>
</div>

<?php
$this->registerJs(<<<JS
function atualizarStatusRequisicoes() {
    document.querySelectorAll('.requisicao-status').forEach(span => {
        const numero = span.dataset.numero;
        fetch('index.php?r=mxm/reqcompra-rco/status-requisicao-ajax&numero=' + numero)
            .then(response => response.json())
            .then(data => {
                if (data?.statusHtml) {
                    span.outerHTML = data.statusHtml;
                }
            })
            .catch(() => {
                span.outerHTML = '<span class="badge bg-danger px-2 py-1">Erro</span>';
            });
    });
}

document.addEventListener('DOMContentLoaded', atualizarStatusRequisicoes);
setInterval(atualizarStatusRequisicoes, 60000);
setInterval(() => {
    $.pjax.reload({container: '#painel-requisicoes'});
}, 300000);
function rolarPainelAutomaticamente() {
    const scrollStep = 1;
    const delay = 30; // milissegundos entre cada scroll
    const pauseAtEnd = 2000; // milissegundos para pausar no fim

    const interval = setInterval(() => {
        window.scrollBy(0, scrollStep);

        // Quando chega ao final da página
        if ((window.innerHeight + window.scrollY) >= document.body.scrollHeight) {
            clearInterval(interval);
            setTimeout(() => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
                setTimeout(rolarPainelAutomaticamente, 1000);
            }, pauseAtEnd);
        }
    }, delay);
}

// Inicia rolagem automática
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(rolarPainelAutomaticamente, 3000); // pequeno delay inicial
});

JS, View::POS_END);
?>
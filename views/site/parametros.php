<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->title = 'Parâmetros do Sistema';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold text-secondary"><i class="bi bi-sliders me-2"></i>Parâmetros do Sistema</h2>
        <p class="text-muted">Configure os elementos básicos utilizados nos Processos Licitatórios</p>
    </div>

    <div class="row g-4 justify-content-center">

        <?php
        $parametros = [
            ['icon' => 'calendar2-week', 'label' => 'Ano', 'url' => ['/base/ano/index']],
            ['icon' => 'journal-text', 'label' => 'Artigo', 'url' => ['/base/artigo/index']],
            ['icon' => 'buildings', 'label' => 'Empresa', 'url' => ['/base/empresa/index']],
            ['icon' => 'person', 'label' => 'Comprador', 'url' => ['/base/comprador/index']],
            ['icon' => 'tag-fill', 'label' => 'Modalidade', 'url' => ['/base/modalidade/index']],
            ['icon' => 'tags', 'label' => 'Valor Limite', 'url' => ['/base/modalidade-valorlimite/index']],
            ['icon' => 'diagram-2', 'label' => 'Segmento', 'url' => ['/base/ramo/index']],
            ['icon' => 'lightning-charge-fill', 'label' => 'Recursos', 'url' => ['/base/recursos/index']],
        ];

        foreach ($parametros as $param) {
            echo Html::beginTag('div', ['class' => 'col-md-4']);
            echo Html::a(
                Html::tag(
                    'div',
                    Html::tag(
                        'div',
                        Html::tag('i', '', ['class' => 'bi bi-' . $param['icon'] . ' display-4 text-primary mb-3']) .
                            Html::tag('h5', $param['label'], ['class' => 'card-title']) .
                            Html::tag('p', 'Gerencie o cadastro de ' . strtolower($param['label']) . '.', ['class' => 'card-text text-muted']),
                        ['class' => 'card-body text-center']
                    ),
                    ['class' => 'card bg-light shadow-lg border-0 hover-shadow rounded-4 h-100 p-4']
                ),
                Url::to($param['url']),
                ['class' => 'text-decoration-none']
            );
            echo Html::endTag('div');
        }
        ?>
    </div>
</div>

<style>
    .hover-shadow {
        transition: all 0.2s ease-in-out;
    }

    .hover-shadow:hover {
        transform: translateY(-4px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
    }
</style>
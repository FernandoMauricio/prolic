<?php

use yii\web\View;

?>
<style>
    /* 1) Reduce o tamanho do número e aumenta um pouco o peso */
    .card .card-body h2 {
        font-size: 1.5rem;
        font-weight: 600;
        line-height: 1.1;
    }

    /* 2) Ajusta o subtítulo para ficar proporcional ao novo tamanho */
    .card .card-body h6.card-subtitle {
        font-size: 1rem;
        margin-bottom: .5rem;
    }

    /* 3) Default para subtítulos nos cards brancos */
    .card .card-body .card-subtitle {
        color: #6c757d;
        /* cinza bootstrap */
    }

    /* 4) Força subtítulo branco nos cards coloridos (saldo) */
    #card-saldo.text-bg-success .card-subtitle,
    #card-saldo.text-bg-danger .card-subtitle {
        color: rgba(255, 255, 255, 0.85) !important;
    }

    /* 5) Pequeno padding extra para harmonizar o espaço */
    .card .card-body {
        padding: 1.25rem;
    }
</style>

<div class="row g-3 mb-4">
    <!-- Valor Limite -->
    <div class="col-lg-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Valor Limite</h6>
                <h2
                    id="card-valor-limite"
                    data-valor="<?= $model->valor_limite ?>"
                    class="display-4 mb-0">
                    <?= Yii::$app->formatter->asCurrency($model->valor_limite) ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Limite Apurado -->
    <div class="col-lg-4">
        <div class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Limite Apurado</h6>
                <h2
                    id="card-limite-apurado"
                    data-valor="<?= $model->valor_limite_apurado ?>"
                    class="display-4 mb-0">
                    <?= Yii::$app->formatter->asCurrency($model->valor_limite_apurado) ?>
                </h2>
            </div>
        </div>
    </div>

    <!-- Saldo Dinâmico -->
    <div class="col-lg-4">
        <div
            id="card-saldo"
            class="card shadow-sm text-center">
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Saldo</h6>
                <h2 id="card-saldo-valor" class="display-4 mb-0">
                    <!-- será preenchido por JS -->
                </h2>
            </div>
        </div>
    </div>
</div>

<hr>

<!-- Campos que serão salvos no banco (inalterados) -->
<div class="row g-3">
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valorestimado')
            ->textInput(['id' => 'processolicitatorio-valorestimado']) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valoraditivo')
            ->textInput(['id' => 'processolicitatorio-valoraditivo']) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valorefetivo')
            ->textInput(['id' => 'processolicitatorio-valorEfetivo']) ?>
    </div>
</div>


<?php


$js = <<<'JS'
(function(){
  // 1) Funções no escopo global
  window.formatarMoeda = function(v) {
    return new Intl.NumberFormat('pt-BR',{
      style: 'currency',
      currency: 'BRL'
    }).format(v);
  };

  window.calcularValores = function() {
    // pega os valores dos mini-cards
    var valorLimite         = parseFloat($('#card-valor-limite').data('valor')) || 0;
    var valorLimiteApurado  = parseFloat($('#card-limite-apurado').data('valor')) || 0;

    // pega os campos de entrada, removendo qualquer máscara
    var valorEstimado  = parseFloat(
      $('#processolicitatorio-valorestimado').val().replace(/[^\d\-\.]/g,'')
    ) || 0;
    var valorAdicional = parseFloat(
      $('#processolicitatorio-valoraditivo').val().replace(/[^\d\-\.]/g,'')
    ) || 0;

    // *** o cálculo do saldo ***
    var valorSaldo = valorLimite 
                     - valorLimiteApurado 
                     - (valorEstimado + valorAdicional);

    // atualiza o mini-card
    $('#card-saldo-valor').text( formatarMoeda(valorSaldo) );
    $('#card-saldo')
      .removeClass('text-bg-success text-bg-danger')
      .addClass(valorSaldo > 0 ? 'text-bg-success' : 'text-bg-danger');
  };

  // 2) Dispara no carregamento inicial
  $(function(){
    window.calcularValores();
  });

  // 3) Recalcula sempre que o usuário digita em estimado ou aditivo
  $('#processolicitatorio-valorestimado, #processolicitatorio-valoraditivo')
    .on('input', window.calcularValores);

  // 4) Recalcula também depois do AJAX do DepDrop
  //    (caso você esteja usando pluginEvents:depdrop:afterChange, 
  //     apenas certifique-se de chamar window.calcularValores() lá)
})();
JS;

$this->registerJs($js, View::POS_END);
?>
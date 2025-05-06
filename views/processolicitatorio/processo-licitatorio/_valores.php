<div class="row g-3">
    <div class="col-lg-4">
        <?= $form->field($model, 'valor_limite')->textInput(['id' => 'processolicitatorio-valor_limite', 'disabled' => true, 'oninput' => 'calcularValores()']) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'valor_limite_apurado')->textInput(['id' => 'processolicitatorio-valor_limite_apurado', 'disabled' => true, 'oninput' => 'calcularValores()']) ?>
    </div>
    <div class="col-lg-4">
        <?= $form->field($model, 'valor_saldo')->textInput(['id' => 'processolicitatorio-valor_saldo', 'disabled' => true]) ?>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valorestimado')->textInput(['id' => 'processolicitatorio-valorestimado', 'oninput' => 'calcularValores()']) ?>
    </div>

    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valoraditivo')->textInput(['id' => 'processolicitatorio-valoraditivo', 'oninput' => 'calcularValores()']) ?>
    </div>

    <div class="col-lg-4">
        <?= $form->field($model, 'prolic_valorefetivo')->textInput(['id' => 'processolicitatorio-valorEfetivo', 'oninput' => 'calcularValores()']) ?>
    </div>
</div>


<script>
    // Função para formatar o valor com a moeda brasileira (R$)
    function formatarMoeda(valor) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(valor);
    }

    function calcularValores() {
        // Obtendo os valores dos campos e tratando valores vazios ou não numéricos
        var valorLimite = parseFloat(document.getElementById('processolicitatorio-valor_limite').value.replace(/[^\d.-]/g, '')) || 0;
        var valorLimiteApurado = parseFloat(document.getElementById('processolicitatorio-valor_limite_apurado').value.replace(/[^\d.-]/g, '')) || 0;
        var valorEstimado = parseFloat(document.getElementById('processolicitatorio-valorestimado').value.replace(/[^\d.-]/g, '')) || 0;
        var valorAdicional = parseFloat(document.getElementById('processolicitatorio-valoraditivo').value.replace(/[^\d.-]/g, '')) || 0;

        // Calculando o valor saldo: (valor_limite - valor_limite_apurado) - (valor_estimado + valor_aditivo)
        var valorSaldo = valorLimite - valorLimiteApurado - (valorEstimado + valorAdicional);

        // Atualizando o campo valor saldo com a formatação de moeda
        document.getElementById('processolicitatorio-valor_saldo').value = formatarMoeda(valorSaldo.toFixed(2));
    }
</script>
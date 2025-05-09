(function () {
    window.formatarMoeda = function (v) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(v);
    };

    window.calcularValores = function (mostrarAlerta = true) {
        var vl = parseFloat($('#card-valor-limite').data('valor')) || 0;
        var va = parseFloat($('#card-limite-apurado').data('valor')) || 0;
        var saldo = vl - va;

        $('#card-saldo-valor').text(formatarMoeda(saldo));
        $('#card-saldo').toggleClass('text-bg-success', saldo > 0).toggleClass('text-bg-danger', saldo <= 0);
        $('#processolicitatorio-valor_limite').val(vl);
        $('#processolicitatorio-valor_limite_apurado').val(va);
        $('#processolicitatorio-valor_saldo').val(saldo);

        if (mostrarAlerta && saldo <= 0) {
            exibirAlertaSaldoNegativo('O <strong>valor apurado</strong> não pode ser igual ou superior ao <strong>limite disponível</strong>.');
        } else {
            $('#saldo-alerta-container').empty();
        }
    };

    $(function () {
        calcularValores(false); // não mostra alerta ao abrir
    });

})();

(function () {
    window.formatarMoeda = function (v) {
        return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(v);
    };

    function ajustarFonteSeNecessario(id) {
        const texto = $(id).text().trim();
        if (texto.length > 15) {
            $(id).addClass('texto-ajustado');
        } else {
            $(id).removeClass('texto-ajustado');
        }
    }

    window.calcularValores = function (mostrarAlerta = true) {
        var vl = parseFloat($('#card-valor-limite').data('valor')) || 0;
        var va = parseFloat($('#card-limite-apurado').data('valor')) || 0;
        var tetoIlimitado = vl >= 999999999.99;

        var ve = parseFloat($('#processolicitatorio-valorestimado').val()?.replace(/[^\d\-.]/g, '') || 0);
        var efetivo = parseFloat($('#processolicitatorio-prolic_valorefetivo').val()?.replace(/[^\d\-.]/g, '') || 0);

        // valor total realmente utilizado
        var valorUtilizado = efetivo > 0 ? efetivo : ve;

        // saldo atualizado
        var saldo = vl - va - valorUtilizado;

        if (tetoIlimitado) {
            $('#card-valor-limite').html('<span class="text-muted fst-italic">(não aplicável)</span>');
            $('#card-valor-limite-wrapper').addClass('text-bg-warning');

            $('#card-saldo-valor').html('<span class="text-muted fst-italic">(não aplicável)</span>');
            $('#card-saldo')
                .removeClass('text-bg-success text-bg-danger')
                .addClass('text-bg-warning');
        } else {
            $('#card-valor-limite-wrapper').removeClass('text-bg-warning');
            $('#card-saldo-valor').text(formatarMoeda(saldo));
            $('#card-saldo')
                .removeClass('text-bg-warning')
                .toggleClass('text-bg-success', saldo > 0)
                .toggleClass('text-bg-danger', saldo <= 0);
        }

        ajustarFonteSeNecessario('#card-valor-limite');
        ajustarFonteSeNecessario('#card-limite-apurado');
        ajustarFonteSeNecessario('#card-saldo-valor');

        $('#processolicitatorio-valor_limite').val(vl);
        $('#processolicitatorio-valor_limite_apurado').val(va);
        $('#processolicitatorio-valor_saldo').val(saldo);

        // Economia: diferença entre estimado e efetivo
        const economiaEl = $('#economia-info');
        if (ve > 0 && efetivo > 0 && efetivo < ve) {
            const economia = ve - efetivo;
            economiaEl
                .html('<i class="bi bi-cash-coin me-1"></i> Economia estimada: <strong>' + formatarMoeda(economia) + '</strong>')
                .fadeIn(200);
        } else {
            economiaEl.stop(true, true).hide().html('');
        }


        if (mostrarAlerta && saldo <= 0 && !tetoIlimitado) {
            exibirAlertaSaldoNegativo('O <strong>valor apurado</strong> não pode ser igual ou superior ao <strong>limite disponível</strong>.');
        } else {
            $('#saldo-alerta-container').empty();
        }
    };

    $(function () {
        calcularValores(false); // não mostra alerta ao abrir

        // Ativa o cálculo em tempo real nos inputs
        $(document).on('input', '#processolicitatorio-valorestimado, #processolicitatorio-prolic_valorefetivo', function () {
            window.calcularValores();
        });
    });
})();

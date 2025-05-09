function configurarValidacaoSaldo() {
    const $form = $('#form-gerar-processo');
    const $spinner = $('#spinner-botao');
    const $botao = $('#botao-submit');

    if ($form.length === 0) return;

    $form.off('beforeSubmit.saldo'); // evita múltiplos binds
    $form.on('beforeSubmit.saldo', function () {
        const saldo = parseFloat($('#processolicitatorio-valor_saldo').val()) || 0;

        if (saldo <= 0) {
            return false; // saldo zerado ou negativo — não envia
        }

        // saldo válido — mostra spinner e desabilita botão
        $spinner.removeClass('d-none');
        $botao.prop('disabled', true);
        return true;
    });
}

// executa ao carregar a página
document.addEventListener('DOMContentLoaded', configurarValidacaoSaldo);

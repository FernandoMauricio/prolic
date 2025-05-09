// saldo-validacao.js

function configurarValidacaoSaldo() {
    const $form = $('#form-gerar-processo');
    if ($form.length === 0) return;

    $form.off('beforeSubmit.saldo'); // evita duplicação
    $form.on('beforeSubmit.saldo', function () {
        const saldo = parseFloat($('#processolicitatorio-valor_saldo').val()) || 0;
        return saldo > 0;
    });
}

// executa se for carregado diretamente
document.addEventListener('DOMContentLoaded', configurarValidacaoSaldo);

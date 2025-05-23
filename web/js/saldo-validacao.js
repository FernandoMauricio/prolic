function configurarValidacaoSaldo() {
    const $form = $('#form-gerar-processo');
    const $cards = $('#cards-financeiros');
    const $infoSituacao = $('#info-artigo-situacao');
    const $alertaSaldo = $('#saldo-alerta');

    if ($form.length === 0) return;

    $form.off('beforeSubmit.saldo'); // evita múltiplos binds
    $form.on('beforeSubmit.saldo', function () {
        const saldo = parseFloat($('#processolicitatorio-valor_saldo').val()) || 0;
        const tipoArtigo = $('#processolicitatorio-artigo_id').data('tipo-artigo') || '';

        const ehSituacao = tipoArtigo.toLowerCase().includes('situação');

        if (ehSituacao) {
            // Oculta cards financeiros e alerta
            $cards.hide();
            $alertaSaldo.hide();
            $infoSituacao.removeClass('d-none');

            console.log('Validação ignorada: artigo do tipo "situação".');
            return true; // ignora a validação
        } else {
            // Restaura normalmente
            $cards.show();
            $alertaSaldo.show();
            $infoSituacao.addClass('d-none');
        }

        if (saldo <= 0) {
            $alertaSaldo.addClass('animate__shakeX');
            return false;
        }

        // Mostrar overlay de transição
        const spinnerOverlay = $(`
            <div id="overlay-loading" style="
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(255,255,255,0.8);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        `);
        $('body').append(spinnerOverlay);

        return true;
    });
}

// executa ao carregar a página
document.addEventListener('DOMContentLoaded', configurarValidacaoSaldo);

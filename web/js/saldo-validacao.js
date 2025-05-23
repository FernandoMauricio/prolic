function configurarValidacaoSaldo() {
    const $form = $('#form-gerar-processo');
    const $cards = $('#cards-financeiros');
    const $infoSituacao = $('#info-artigo-situacao');
    const $alertaSaldo = $('#saldo-alerta');
    const $spinner = $('#spinner-botao');

    if ($form.length === 0) return;

    $form.off('beforeSubmit.saldo');

    $form.on('beforeSubmit.saldo', function () {
        const saldo = parseFloat($('#processolicitatorio-valor_saldo').val()) || 0;
        const tipoArtigo = ($('#processolicitatorio-artigo_id').data('tipo-artigo') || '').toLowerCase();
        const ehSituacao = tipoArtigo.includes('situação');

        if (ehSituacao) {
            $cards.hide();
            $alertaSaldo.hide();
            $infoSituacao.removeClass('d-none');
            $('#saldo-alerta-container').empty();

            // Garantir overlay também para "situação"
            if ($('#overlay-loading').length === 0) {
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
            }

            return true;
        }

        $cards.show();
        $alertaSaldo.show();
        $infoSituacao.addClass('d-none');

        if (saldo <= 0) {
            $alertaSaldo.addClass('animate__shakeX');
            return false;
        }

        if ($('#overlay-loading').length === 0) {
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
        }

        return true;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    configurarValidacaoSaldo();

    $('#form-gerar-processo').on('submit', function (e) {
        if (!$(this).data('submitted')) {
            const result = $('#form-gerar-processo').triggerHandler('beforeSubmit.saldo');
            if (result === false) {
                e.preventDefault();
                return false;
            }
            $(this).data('submitted', true);
        }
    });
});

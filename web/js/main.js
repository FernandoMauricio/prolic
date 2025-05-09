// MODAL principal
$(function () {
    $('#modalButton').click(function () {
        $('#modal').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'), function () {
                // Verifica se é o modal específico
                if ($('#form-gerar-processo').length > 0) {
                    if (typeof configurarValidacaoSaldo === 'function') {
                        configurarValidacaoSaldo();
                    }

                    // Reativar Select2 se for necessário aqui também
                    $('[data-krajee-select2]').each(function () {
                        const id = $(this).attr('id');
                        const configVar = $(this).data('krajee-select2');
                        if (window[configVar]) {
                            $(this).select2('destroy').select2(window[configVar]);
                        }
                    });
                }
            });
    });
});

//MODAL
$(function () {
    $('#modalButton2').click(function () {
        $('#modal2').modal('show')
            .find('#modalContent2')
            .load($(this).attr('value'));
    });
});

// --- Delete action (bootbox) ---
yii.confirm = function (message, ok, cancel) {

    bootbox.confirm(
        {
            message: message,
            buttons: {
                confirm: {
                    label: "OK"
                },
                cancel: {
                    label: "Cancel"
                }
            },
            callback: function (confirmed) {
                if (confirmed) {
                    !ok || ok();
                } else {
                    !cancel || cancel();
                }
            }
        }
    );
    // confirm will always return false on the first call
    // to cancel click handler
    return false;
};
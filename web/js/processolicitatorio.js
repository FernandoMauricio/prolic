$(function() {

    $('#processolicitatorio-valor_limite_hidden').keyup(function() {
       updateTotal();
    });

    $('#processolicitatorio-valor_limite_hidden_teste').keyup(function() {
       updateTotal();
    });


    var updateTotal = function () {

      var valor_limite_hidden       = parseFloat($('#processolicitatorio-valor_limite_hidden').val());
      var valor_limite_hidden_teste = parseFloat($('#processolicitatorio-valor_limite_hidden_teste').val());

console.log(valor_limite_hidden_teste);
      $('#processolicitatorio-valor_limite_hidden-disp').val(valor_limite_hidden_teste);
    };
 });

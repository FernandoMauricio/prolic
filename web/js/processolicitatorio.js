// $(function() {

//     $('#processolicitatorio-prolic_valorestimado-disp').ready(function() {
//        updateTotal();
//       $('#processolicitatorio-prolic_valorestimado-disp').keyup(function() {
//        updateTotal();
//     })});

//     $('#processolicitatorio-prolic_valoraditivo-disp').ready(function() {
//        updateTotal();
//       $('#processolicitatorio-prolic_valoraditivo-disp').keyup(function() {
//        updateTotal();
//     })});

//     var updateTotal = function () {

//       var prolic_valorestimado = parseFloat($('#processolicitatorio-prolic_valorestimado-disp').val());
//       var prolic_valoraditivo  = parseFloat($('#processolicitatorio-prolic_valoraditivo-disp').val());

//       var prolic_valorefetivo = prolic_valorestimado + prolic_valoraditivo;

//       if (isNaN(prolic_valorefetivo) || prolic_valorefetivo < 0) {
//           prolic_valorefetivo = 0;
//       }

//       $('#processolicitatorio-prolic_valorefetivo-disp').val(prolic_valorefetivo);
//       $('#processolicitatorio-prolic_valorefetivo_hidden').val(prolic_valorefetivo);
//     };
//  });

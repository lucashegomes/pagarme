var bsObj = {
    hostedPaymentFields: {
        ccn: "ccn", 
        cvv: "cvv", 
        exp: "exp"  
    },
    style: {
        "input": {
            "color" : "#636871 !important",
            "font-size" : "1.3rem",
            "font-weight" : "400",
            "line-height" : "1"
        }
    },
    ccnPlaceHolder: "", 
    cvvPlaceHolder: "", 
    expPlaceHolder: ""
};

bluesnap.hostedPaymentFieldsCreation ("672d02b47ae64498c30ec3312ce57976bf171d86a1b75c805c212b715352639d_", bsObj);

//Cpf and CNPJ mask and listener config
var options = {
    onKeyPress: function (cpf, ev, el, op) {
        var masks = ['000.000.000-000', '00.000.000/0000-00'];
        $('#cpf-cnpj').mask((cpf.length > 14) ? masks[1] : masks[0], op);
    }
}
    
if ($('#cpf-cnpj').length > 11) {
    $('#cpf-cnpj').mask('00.000.000/0000-00', options);
} else {
    $('#cpf-cnpj').mask('000.000.000-00#', options);
}

//Cellphone mask
$("#cellphone-number").mask("(00) 00000-0000");

//Pop-in info cvv
$("#help-cvv").hover(
    function(){
      $(".popup-info.card-info").show();
    },
    function(){
      $(".popup-info.card-info").hide();
    }
);

$(".payment-flags .credit-card").click(function()
{
    $("div.paypal-panel").addClass('hide');
    $("div.boleto-panel").addClass('hide');
    $("div.card-panel").removeClass('hide');
    event.stopPropagation();
});

$('.payment-flags div.credit-card').click(function(event){
    var imageSource = event.target.getAttribute('src');

    $('#card-logo img').attr('src', imageSource).css('height', '32px');
    $('#card-logo').css('padding', 0);

});

$(".payment-flags .paypal").click(function(event)
{
    $("div.paypal-panel").removeClass('hide');
    $("div.card-panel").addClass('hide');
    $("div.boleto-panel").addClass('hide');
    event.stopPropagation();
});


$(".payment-flags .boleto").click(function(event)
{
    $("div.boleto-panel").removeClass('hide');
    $("div.card-panel").addClass('hide');
    $("div.paypal-panel").addClass('hide');
    event.stopPropagation();
});


// $("form").submit(function(e){
//     e.preventDefault();
// });

function showInstallments()
{
    var total = 250.00;
    var taxa = 1.531;

    for (var i = 1; i <= 12; i++) {

        var installmentValue = total;

        if (i > 1) {
            installmentValue = (total * Math.pow(1 + (taxa / 100), i)) / i;
        }

        $(".select-installments").prepend(
            '<option value=' + i + '>' + i + 'x de R$' + installmentValue.toFixed(2) + '</option>'
        );
    }

    $(".select-installments").removeClass('hide');
    $(".select-installments, label.text-installments").removeClass('hide');
        
    $('.edit-installments').addClass('hide');
}

function submitForm(element) {
    
    // var method = element.getAttribute('method');
    // var body = $('body');
    // var form = $('#checkout-form');

    // body.append(
    //     '<input type="hidden" name="payment-method" value="' + method + '">'
    // );

    // form.submit();

    // $.post('./index.php', );
}

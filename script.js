$(document).ready(function(){

    var address = {
        'address' : 'Rua Ernesto Hans',
        'address-number' : '20',
        'address-state' : 'SP',
        'address-city' : 'Limeira',
        'address-neighborhood' : 'Centro',
        'address-cep' : '01001-000',
    };

    var items = {
        0 : {
            'product-id' : '76332',
            'product-name' : 'Promoção x',
            'unit-price' : '22.88',
            'quantity' : '4',
        },
        1 : {
            'product-id' : '883232',
            'product-name' : 'Promoção Y',
            'unit-price' : '145.88',
            'quantity' : '1',
        }
    }
    
    var amount = {'amount' : 250.00};

    appendElementToForm(address);
    appendElementToForm(amount);
    appendElementToForm(items, true);
});

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

function appendElementToForm(object, multidimentional = false)
{
    var form = $('#checkout-form');
    
    if (multidimentional) {

        Object.entries(object).forEach(([index, item]) => { 
        
            Object.entries(item).forEach(([key, value]) => { 
                form.append('<input type="hidden" name="' + key + '" value="' + value + '" >');
            });
    
        });

        return;
    } 

    Object.entries(object).forEach(([key, value]) => { 
        form.append('<input type="hidden" name="' + key + '" value="' + value + '" >');
    });
}
//Initiates the Hosted Payment Fields script with your Hosted Payment Fields token and defines event handlers and styles

var bsObj = {
        hostedPaymentFields: {
            ccn: "ccn", // name cannot contain spaces or special characters 
            cvv: "cvv", // name cannot contain spaces or special characters 
            exp: "exp"  // name cannot contain spaces or special characters 
        },
        onFieldEventHandler: {
            onFocus: function(tagId) { 
                // Handle focus 
                if (tagId == "ccn") {
                    $( "#card-number" ).addClass( "hosted-field-focus" );
                } else if (tagId == "exp") {
                    $( "#exp-date" ).addClass( "hosted-field-focus" ); 
                } else if (tagId == "cvv") {
                    $( "#cvv" ).addClass( "hosted-field-focus" );
                }  
            },
            onBlur: function(tagId) {
                // Handle blur  
    if (tagId == "ccn") {
        $( "#card-number" ).removeClass( "hosted-field-focus" );
    } else if (tagId == "exp") {
        $( "#exp-date" ).removeClass( "hosted-field-focus" ); 
    } else if (tagId == "cvv") {
        $( "#cvv" ).removeClass( "hosted-field-focus" );
    }
            },
            onError: function(tagId, errorCode) { 
                // Handle a change in validation
                /* error codes:
                    "001" --> "Please enter a valid credit card number";			
                    "002" --> "Please enter the CVV/CVC of your card";			
                    "003" --> "Please enter your credit card's expiration date";			
                    "004" --> "Session expired please refresh page to continue";			
                    "005" --> "Internal server error please try again later";			
                */

    if (tagId == "ccn" && errorCode == "001") {
        $( "#card-number" ).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" );
        $( "#card-help" ).text('Please enter a valid credit card number');
    } else if (tagId == "exp" && errorCode == "003") {
        $( "#exp-date" ).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('Please enter the expiration date (MM/YYYY)'); 
    } else if (tagId == "cvv" && errorCode == "002" ) {
        $( "#cvv" ).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('Please enter the CVV/CVC of your card');
    }
            },
            onEmpty: function(tagId, errorCode) { 
                // Handle a change in validation  
    if (tagId == "ccn" && errorCode == "001") {
        $( "#card-number" ).removeClass( "hosted-field-focus hosted-field-valid hosted-field-invalid" );
        $( "#card-help" ).text('');
        $('#card-logo img').attr("src", "https://files.readme.io/d1a25b4-generic-card.png");
    } else if (tagId == "exp" && errorCode == "003") {
        $( "#exp-date" ).removeClass( "hosted-field-focus hosted-field-valid hosted-field-invalid" ).next('span').text('');
    } else if (tagId == "cvv" && errorCode == "002" ) {
        $( "#cvv" ).removeClass( "hosted-field-focus hosted-field-valid hosted-field-invalid" ).next('span').text('');
    }
            },
            onType: function(tagId, cardType) {
                // get card type from cardType and display the img accordingly
                
    if (cardType == "AmericanExpress") { $('#card-logo img').attr("src", "https://files.readme.io/97e7acc-Amex.png");
    } else if (cardType == "CarteBleue") { $('#card-logo img').attr("src", "https://files.readme.io/5da1081-cb.png");
    } else if (cardType == "DinersClub") { $('#card-logo img').attr("src", "https://files.readme.io/8c73810-Diners_Club.png");
    } else if (cardType == "Discover") { $('#card-logo img').attr("src", "https://files.readme.io/caea86d-Discover.png");
    } else if (cardType == "JCB") { $('#card-logo img').attr("src", "https://files.readme.io/e076aed-JCB.png");
    } else if (cardType == "MaestroUK") { $('#card-logo img').attr("src", "https://files.readme.io/daeabbd-Maestro.png");
    } else if (cardType == "MasterCard") { $('#card-logo img').attr("src", "https://files.readme.io/5b7b3de-Mastercard.png");
    } else if (cardType == "Solo") { $('#card-logo img').attr("src", "https://sandbox.bluesnap.com/services/hosted-payment-fields/cc-types/solo.png");
    } else if (cardType == "Visa") { $('#card-logo img').attr("src", "https://files.readme.io/9018c4f-Visa.png");
    }
            },
            onValid: function(tagId) {
                // Handle a change in validation
    if (tagId == "ccn") {
        $( "#card-number" ).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" )
        $( "#card-help" ).text('');
    } else if (tagId == "exp") {
        $( "#exp-date" ).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text(''); 
    } else if (tagId == "cvv") {
        $( "#cvv" ).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text('');
    }
            }
        },				  					  
        //styling is optional
        style: {
            // Styling all Hosted Payment Field inputs
            "input": {
                "font-size": "14px",
    "font-family": "Helvetica Neue,Helvetica,Arial,sans-serif",
    "line-height": "1.42857143",
    "color": "#555"
            },
            
            // Styling a specific field
            /*"#ccn": {
                
            },*/
            
            // Styling Hosted Payment Field input state
            ":focus": {
                "color": "#555"
            }
        },
ccnPlaceHolder: "4111222233334444", 
cvvPlaceHolder: "123", 
expPlaceHolder: "MM/YYYY"
    };
    bluesnap.hostedPaymentFieldsCreation ("672d02b47ae64498c30ec3312ce57976bf171d86a1b75c805c212b715352639d_", bsObj);//insert your Hosted Payment Fields token


// Submits the credit card, expiration date and CVV data to BlueSnap, where it will be associated with the Hosted Payment Fields token. On success, a card data object containing the card type and last four digits will be be passed to the function (cardData). You should add a function to do the final submit of the form after the card type and last four digits are received.


    
function do_when_clicking_submit_button(){						   
    bluesnap.submitCredentials( function(cardData){
        console.log('the card type is ' + cardData.ccType + ' and last 4 digits are ' + cardData.last4Digits + ' and exp is ' + cardData.exp + ' after that I can call final submit');
    });
    /* submit the form
    return true; */
    return false; // don't submit the form
}					

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
    var total = 197.00;
    var taxa = 4.40;

    for (var i = 1; i <= 12; i++) {

        var installmentValue = total;

        if (i > 1) {
            installmentValue = (total + (total * 0.01 * taxa)) / i;
        }

        $(".select-installments").prepend(
            '<option value=' + i + '>' + i + 'x de R$' + installmentValue.toFixed(2) + '</option>'
        );
    }

    $(".select-installments").removeClass('hide');
        
    $('.edit-installments').addClass('hide');
}
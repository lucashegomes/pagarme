<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>PÁGINA DE CONVERSÃO</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap" rel="stylesheet">    
    <link rel="stylesheet" href="/style.css">
    <link rel="icon" type="image/png" href="/credit-card-flags/gd.png">
</head>

<body>
    <div class="panel panel-default bootstrap-basic">
        <form class="panel-body" action="/pagarme.php" method="POST" id="checkout-form" >
            <div class="row">
                <!-- Nome Completo -->
                <div class="form-group col-md-12">
                    <label for="full-name">Nome Completo</label>
                    <input id="full-name" class="form-control" name="full-name" type="text">
                </div>
                
                <!-- E-mail -->
                <div class="form-group col-md-12">
                    <label for="email">E-mail</label>
                    <input id="email" class="form-control" name="email" type="email">
                </div>
                
                <!-- Celular -->
                <div class="form-group col-md-12">
                    <label for="cellphone-number">Celular</label>
                    <input id="cellphone-number"  class="form-control" name="cellphone-number" type="tel">
                </div>
                
                <!-- CPF ou CNPJ -->
                <div class="form-group col-md-12">
                    <label for="cpf-cnpj">CPF ou CNPJ</label>
                    <input id="cpf-cnpj" class="form-control" name="cpf-cnpj" type="text">
                </div>

                <div class="security-message form group col-md-12">
                    <svg class="lock" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                        <path d="M75 143c-.3 0-.5 0-.8-.1-8.4-2.5-16.4-6.7-23.8-12.5C32 116 21.1 92.8 21.1 68.4V24c0-1 .5-1.9 1.4-2.3 36.3-19.5 68.6-19.5 104.9 0 .9.5 1.4 1.4 1.4 2.3v44.4c0 24.4-11 47.5-29.3 62-7.4 5.8-15.4 10-23.8 12.5-.2 0-.4.1-.7.1zM26.5 25.6v42.8c0 22.8 10.2 44.4 27.3 57.8 6.6 5.2 13.8 9 21.3 11.4 7.5-2.3 14.6-6.1 21.3-11.4 17.1-13.4 27.3-35 27.3-57.8V25.6c-34.1-17.9-63.3-17.9-97.2 0z"></path><path class="st0" d="M97.9 100.2H52.1c-1.5 0-2.6-1.2-2.6-2.6V60.9c0-1.5 1.2-2.6 2.6-2.6h45.7c1.5 0 2.6 1.2 2.6 2.6v36.6c.1 1.5-1.1 2.7-2.5 2.7zm-43.1-5.3h40.4V63.6H54.8v31.3z"></path><path class="st0" d="M88.7 63.6H61.3c-1.5 0-2.6-1.2-2.6-2.6V50.2c0-9 7.3-16.4 16.4-16.4s16.4 7.3 16.4 16.4v10.7c-.1 1.5-1.3 2.7-2.8 2.7zm-24.8-5.3H86v-8c0-6.1-5-11.1-11.1-11.1s-11.1 5-11.1 11.1v8z"></path>
                    </svg>
                    <span>Seus dados serão mantidos em sigilo</span>
                </div>
            </div>
            
            <div class="row">
                <span class="payment-method text-muted">SELECIONE UM MÉTODO DE PAGAMENTO</span>
                <hr>
         
                <!-- Bandeiras de cartÃ£o-->
                <div class="form-group col-md-12 payment-flags">
                    <div class="credit-card"><img class="mastercard" src="/credit-card-flags/mastercard.svg"/></div>
                    <div class="credit-card"><img class="visa" src="/credit-card-flags/visa.svg"/></div>
                    <div class="credit-card"><img class="amex" src="/credit-card-flags/amex.svg"/></div>
                    <div class="credit-card"><img class="elo" src="/credit-card-flags/elo.svg"/></div>
                    <div class="credit-card"><img class="diners" src="/credit-card-flags/diners.svg"/></div>
                    <div class="credit-card"><img class="hiper" src="/credit-card-flags/hipercard.svg"/></div>
                    <div><img class="paypal" src="/credit-card-flags/paypal.svg"/></div>
                    <div><img class="boleto" src="/credit-card-flags/boleto.svg"/></div>
                </div>

                <div class="card-panel">
                    <!--Hosted Field for CC number-->
                    <div class="form-group col-md-12">
                        <label for="card-number">Número do Cartão</label>
                        <div class="input-group">
                            <div id="card-logo" class="input-group-addon"><img src="https://files.readme.io/d1a25b4-generic-card.png" height="20px"></div>
                            <!-- <div class="form-control input" id="card-number" name="card-number" data-bluesnap="ccn"></div> -->
                            <input type="text" class="form-control" id="card-number" name="card-number">
                        </div>
                    </div>
                    
                    <!-- Validade do cartï¿½o-->
                    <div class="form-group col-xs-7">
                        <label for="exp-date">Validade (MM/AA)</label>
                        <!-- <div class="form-control input" id="exp-date" name="exp-date" data-bluesnap="exp"></div> -->
                        <input type="text" class="form-control" id="exp-date" name="exp-date">
                    </div>
                    
                    <!-- Cï¿½digo de seguranï¿½a-->
                    <div class="form-group col-xs-5 cvv">
                        <label for="cvv">Cod Segurança</label>
                        <i id="help-cvv" class="fa fa-lg fa-question-circle text-info"></i>
                        <!-- <div class="form-control input" id="cvv" name="cvv" data-bluesnap="cvv"></div> -->
                        <input type="text" class="form-control" id="cvv" name="cvv">
                    </div>
    
                    <div class="popup-info card-info">
                        <img src="/credit-card-flags/creditcard-cvv.png">
                    </div>
                    
                    <!-- NÃºmero do cartÃ£o -->
                    <div class="form-group col-md-12">
                        <label for="cardholder-name">Nome impresso no cartão</label>
                        <input type="text" class="form-control" id="cardholder-name" name="cardholder-name">
                    </div>

                    <!-- PARCELAS -->
                    <div class="form-group col-md-12">
                        <label for="installments" class="text-installments hide">Parcelas</label>
                        <a href="#select-installments" class="edit-installments" onclick="showInstallments()">Editar parcelas</a>
                        <select id="select-installments" class="form-control select-installments hide" name="select-installments"></select>
                    </div>

                    <button class="btn btn-success btn-lg col-xs-12" name="btn-card" onclick="submitForm(this)" id="btn-method-card" method="card">
                        <span>
                            <svg class="lock" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                                <path d="M75 143c-.3 0-.5 0-.8-.1-8.4-2.5-16.4-6.7-23.8-12.5C32 116 21.1 92.8 21.1 68.4V24c0-1 .5-1.9 1.4-2.3 36.3-19.5 68.6-19.5 104.9 0 .9.5 1.4 1.4 1.4 2.3v44.4c0 24.4-11 47.5-29.3 62-7.4 5.8-15.4 10-23.8 12.5-.2 0-.4.1-.7.1zM26.5 25.6v42.8c0 22.8 10.2 44.4 27.3 57.8 6.6 5.2 13.8 9 21.3 11.4 7.5-2.3 14.6-6.1 21.3-11.4 17.1-13.4 27.3-35 27.3-57.8V25.6c-34.1-17.9-63.3-17.9-97.2 0z"></path>
                                <path class="st0" d="M97.9 100.2H52.1c-1.5 0-2.6-1.2-2.6-2.6V60.9c0-1.5 1.2-2.6 2.6-2.6h45.7c1.5 0 2.6 1.2 2.6 2.6v36.6c.1 1.5-1.1 2.7-2.5 2.7zm-43.1-5.3h40.4V63.6H54.8v31.3z"></path>
                                <path class="st0" d="M88.7 63.6H61.3c-1.5 0-2.6-1.2-2.6-2.6V50.2c0-9 7.3-16.4 16.4-16.4s16.4 7.3 16.4 16.4v10.7c-.1 1.5-1.3 2.7-2.8 2.7zm-24.8-5.3H86v-8c0-6.1-5-11.1-11.1-11.1s-11.1 5-11.1 11.1v8z"></path>
                            </svg>
                            Pagar e Receber Agora
                        </span>
                    </button> 
                </div>

                <div class="paypal-panel hide">
                    <!--Hosted Field for CC number-->
                    <div class="form-group col-md-12">
                        <p>Você será redirecionado para o Paypal para concluir o pagamento.</p>
                        <p>Pagamentos via Paypal somente à vista.</p>

                        <button class="btn btn-success btn-lg col-xs-12" name="btn-paypal" onclick="submitForm(this)" id="btn-method-paypal" method="paypal">
                            <span>
                                <svg class="lock" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M75 143c-.3 0-.5 0-.8-.1-8.4-2.5-16.4-6.7-23.8-12.5C32 116 21.1 92.8 21.1 68.4V24c0-1 .5-1.9 1.4-2.3 36.3-19.5 68.6-19.5 104.9 0 .9.5 1.4 1.4 1.4 2.3v44.4c0 24.4-11 47.5-29.3 62-7.4 5.8-15.4 10-23.8 12.5-.2 0-.4.1-.7.1zM26.5 25.6v42.8c0 22.8 10.2 44.4 27.3 57.8 6.6 5.2 13.8 9 21.3 11.4 7.5-2.3 14.6-6.1 21.3-11.4 17.1-13.4 27.3-35 27.3-57.8V25.6c-34.1-17.9-63.3-17.9-97.2 0z"></path>
                                    <path class="st0" d="M97.9 100.2H52.1c-1.5 0-2.6-1.2-2.6-2.6V60.9c0-1.5 1.2-2.6 2.6-2.6h45.7c1.5 0 2.6 1.2 2.6 2.6v36.6c.1 1.5-1.1 2.7-2.5 2.7zm-43.1-5.3h40.4V63.6H54.8v31.3z"></path>
                                    <path class="st0" d="M88.7 63.6H61.3c-1.5 0-2.6-1.2-2.6-2.6V50.2c0-9 7.3-16.4 16.4-16.4s16.4 7.3 16.4 16.4v10.7c-.1 1.5-1.3 2.7-2.8 2.7zm-24.8-5.3H86v-8c0-6.1-5-11.1-11.1-11.1s-11.1 5-11.1 11.1v8z"></path>
                                </svg>
                                Ir para o PayPal
                            </span>
                        </button> 
                    </div>
                </div>

                <div class="boleto-panel hide">
                    <!--Hosted Field for CC number-->
                    <div class="form-group col-md-12">
                        <p>Boleto somente em uma parcela.</p>
                        <p>Pagamentos com Boleto Bancário levam até 3 dias úteis para serem compensados e então terem os produtos liberados.</p>
                        <p>Depois do pagamento, fique atento ao seu e-mail para receber os dados de acesso ao produto (verifique também a caixa de SPAM).</p>

                        <button class="btn btn-success btn-lg col-xs-12" name="btn-boleto" onclick="submitForm(this)" id="btn-method-boleto" method="boleto">
                            <span>
                                <svg class="lock" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M75 143c-.3 0-.5 0-.8-.1-8.4-2.5-16.4-6.7-23.8-12.5C32 116 21.1 92.8 21.1 68.4V24c0-1 .5-1.9 1.4-2.3 36.3-19.5 68.6-19.5 104.9 0 .9.5 1.4 1.4 1.4 2.3v44.4c0 24.4-11 47.5-29.3 62-7.4 5.8-15.4 10-23.8 12.5-.2 0-.4.1-.7.1zM26.5 25.6v42.8c0 22.8 10.2 44.4 27.3 57.8 6.6 5.2 13.8 9 21.3 11.4 7.5-2.3 14.6-6.1 21.3-11.4 17.1-13.4 27.3-35 27.3-57.8V25.6c-34.1-17.9-63.3-17.9-97.2 0z"></path>
                                    <path class="st0" d="M97.9 100.2H52.1c-1.5 0-2.6-1.2-2.6-2.6V60.9c0-1.5 1.2-2.6 2.6-2.6h45.7c1.5 0 2.6 1.2 2.6 2.6v36.6c.1 1.5-1.1 2.7-2.5 2.7zm-43.1-5.3h40.4V63.6H54.8v31.3z"></path>
                                    <path class="st0" d="M88.7 63.6H61.3c-1.5 0-2.6-1.2-2.6-2.6V50.2c0-9 7.3-16.4 16.4-16.4s16.4 7.3 16.4 16.4v10.7c-.1 1.5-1.3 2.7-2.8 2.7zm-24.8-5.3H86v-8c0-6.1-5-11.1-11.1-11.1s-11.1 5-11.1 11.1v8z"></path>
                                </svg>
                                Gerar Boleto
                            </span>
                        </button> 
                    </div>
                </div>

                <div class="form-group col-md-12 security-badges">
                    <div>
                        <svg viewBox="0 0 49 66" width="35" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#318834" fill-rule="evenodd">
                                <path d="M23.603.248c.487-.33 1.127-.33 1.614 0 4.33 2.942 11.72 6.733 22.348 8.088.718.092 1.255.702 1.255 1.424v19.323c0 12.36-5.504 24.063-14.722 31.308-2.906 2.288-5.99 4.077-9.164 5.32-.168.068-.346.1-.524.1-.178 0-.356-.032-.524-.1-3.176-1.243-6.26-3.032-9.162-5.315C5.504 53.147 0 41.445 0 29.085V9.76c0-.722.537-1.332 1.254-1.424C11.882 6.98 19.274 3.19 23.604.248zm.126 5.1C20.07 7.83 13.84 11.03 4.87 12.174c-.608.077-1.06.592-1.06 1.202V29.68c0 10.428 4.643 20.304 12.423 26.417 2.45 1.927 5.05 3.436 7.73 4.487.143.056.293.083.443.083.15 0 .3-.027.44-.083 2.68-1.05 5.28-2.56 7.734-4.487 7.78-6.113 12.423-15.99 12.423-26.417V13.376c0-.61-.453-1.125-1.06-1.202C34.98 11.03 28.743 7.83 25.09 5.35c-.41-.28-.95-.28-1.36 0z"></path>
                                <path d="M39.31 19.275c.46.402.508 1.104.104 1.565L21.66 43.586c-.2.23-.49.367-.797.378-.33 0-.612-.116-.82-.325L12.325 33.5c-.433-.434-.433-1.136 0-1.57.434-.433 1.135-.433 1.57 0l6.602 5.59 17.248-18.147c.404-.46 1.104-.507 1.566-.103z"></path>
                            </g>
                        </svg>
                        <div class="text"><span class="buy"><span>Compra</span></span><span class="safety"><span>100% Segura</span></span>
                        </div>
                    </div>

                    <div>
                        <svg viewBox="0 0 46 60" xmlns="http://www.w3.org/2000/svg">
                            <path d="M45 8C30.372 8 23.83.435 23.768.36c-.38-.456-1.156-.456-1.536 0C22.168.436 15.695 8 1 8c-.552 0-1 .447-1 1v19.085c0 10.433 4.69 20.348 12.546 26.52 3.167 2.49 6.588 4.292 10.17 5.354.093.02.188.04.284.04.096 0 .19-.02.285-.04 3.58-1.07 7.002-2.87 10.17-5.36C41.31 48.43 46 38.52 46 28.08V9c0-.553-.448-1-1-1zM9.286 36.958C9.19 36.986 9.094 37 9 37c-.43 0-.83-.28-.958-.715-.78-2.622-1.11-5.15-1.035-7.96v-.002c.128-4.77 1.492-8.276 4.29-11.035.394-.39 1.027-.383 1.415.01.388.393.383 1.026-.01 1.414-2.408 2.373-3.582 5.444-3.696 9.666-.07 2.592.233 4.923.952 7.337.158.53-.143 1.086-.672 1.243zM18 44.035c-.02.54-.463.965-1 .965h-.035c-.552-.02-.984-.483-.965-1.035.133-3.788.074-8.987-.958-12.247-.03-.096-.053-.167-.066-.217-.28-1.03-.41-2.1-.378-3.17.08-2.77 1.293-6.36 3.827-8.14.453-.32 1.076-.21 1.393.25.318.45.208 1.08-.243 1.39-1.957 1.38-2.915 4.37-2.978 6.56v.01c-.025.87.08 1.75.312 2.606l.04.13c.89 2.834 1.25 7.18 1.05 12.92zm7.913 1c-.02.54-.463.965-1 .965h-.035c-.552-.02-.984-.482-.964-1.035.424-12.19-1.092-16.092-1.107-16.13-.206-.51.036-1.092.543-1.302.51-.214 1.088.026 1.302.53.07.166 1.706 4.192 1.26 16.972zm3.105-1.043c-.552 0-1-.44-1-.993 0-.31-.004-.58-.01-.86-.004-.29-.01-.59-.01-.92l.002-.23c.003-.55.45-1 1-1h.006c.553 0 .997.45.994 1v.224c0 .32.004.604.01.883.004.28.008.56.008.87 0 .55-.448 1-1 1zm.106-5.084H29.1c-.54 0-.986-.432-.998-.977-.115-4.99-.617-7.82-1.018-9.32-.204-.76-.38-1.17-.463-1.37-.49-1.16-1.62-1.91-2.88-1.91-.41 0-.82.08-1.21.25-1.13.476-1.89 1.58-1.92 2.82-.01.41.06.818.21 1.204.39 1.164 1.54 5.517 1.2 15.453-.02.54-.46.965-1 .965h-.033c-.55-.02-.983-.48-.964-1.032.346-9.85-.83-13.963-1.07-14.69-.234-.605-.35-1.27-.33-1.95.055-2.023 1.293-3.833 3.155-4.61.63-.26 1.297-.396 1.98-.396 2.067 0 3.924 1.23 4.73 3.136.1.24.313.74.554 1.64.616 2.304.99 5.69 1.085 9.795.014.554-.423 1.01-.976 1.024zM34 42.035c-.02.54-.463.965-1 .965h-.035c-.552-.02-.984-.482-.965-1.035.258-7.378-.235-11.05-.693-13.426v-.01c-.202-1.05-.49-2-.877-2.92C29.225 22.77 26.76 21 24 21c-.526 0-1.05.057-1.56.17-.54.112-1.072-.223-1.19-.762-.12-.54.22-1.073.76-1.192.65-.144 1.32-.216 1.99-.216 3.578 0 6.748 2.237 8.272 5.84.443 1.05.77 2.134 1 3.317v.003c.71 3.682.934 7.96.727 13.875zm3.065-5.176L37 39.02c-.015.543-.46.973-1 .973h-.027c-.553-.017-.988-.477-.973-1.028l.066-2.178c.11-3.54.19-6.096-.16-8.288-.177-1.1-.42-2.1-.747-3.06-1.39-4.09-5.99-7.67-10.03-7.81-1.59-.07-3.12.22-4.57.82-4.01 1.67-6.62 5.55-6.66 9.89-.01 1.02.12 2.04.41 3.03.18.653.37 1.355.48 2.085.23 1.556.29 4.19.2 8.54-.02.547-.46.98-1 .98h-.02c-.56-.01-.99-.47-.98-1.02.08-4.236.03-6.77-.18-8.21-.09-.61-.26-1.214-.43-1.83-.34-1.176-.5-2.385-.49-3.597.04-5.137 3.14-9.736 7.884-11.718 1.72-.718 3.535-1.05 5.404-.98 4.88.173 10.2 4.286 11.854 9.17.362 1.07.633 2.17.826 3.38.387 2.38.3 5.02.19 8.67zm3.032-5.87H40c-.51 0-.95-.39-1-.91-.21-2.14-.86-4.44-2.047-7.24-2.253-5.33-7.446-8.77-13.23-8.77-1.91 0-3.77.37-5.53 1.103-.67.28-1.27.697-1.91 1.137-.246.17-.494.343-.746.507-.464.3-1.08.167-1.383-.296-.3-.462-.17-1.08.293-1.383.238-.15.47-.31.7-.47.698-.48 1.416-.978 2.278-1.337 2.006-.84 4.126-1.266 6.302-1.266 6.587 0 12.502 3.92 15.07 9.99 1.266 2.99 1.963 5.48 2.192 7.82.05.55-.35 1.04-.9 1.09z" fill="#318834" fill-rule="evenodd"></path>
                        </svg>
                        <div class="text"><span class="buy"><span>Privacidade</span></span><span class="safety"><span>Protegida</span></span>
                        </div>
                    </div>

                    <div>
                        <svg height="41" viewBox="0 0 52 41" width="52" xmlns="http://www.w3.org/2000/svg">
                            <g fill="#318834" fill-rule="evenodd">
                                <path d="M50.632 35.875H49.4v-32.4C49.4 1.557 47.82 0 45.876 0H6.124C4.18 0 2.6 1.558 2.6 3.474v32.4H1.368c-.754 0-1.368.606-1.368 1.35v2.428C0 40.395.614 41 1.368 41h49.264c.754 0 1.368-.605 1.368-1.348v-2.43c0-.742-.614-1.347-1.368-1.347zm-46.3-32.4c0-.975.805-1.767 1.792-1.767h39.752c.987 0 1.79.792 1.79 1.766v32.4H30.51l-.206.567c-.053.146-.198.29-.404.29h-6.933c-.207 0-.35-.144-.404-.29l-.206-.564H4.333v-32.4zM50.268 39.29H1.733v-1.71H21.24c.403.528 1.04.855 1.727.855H29.9c.687 0 1.323-.327 1.726-.854h18.64v1.71z"></path>
                                <path d="M5.2 35.02h41.6V2.563H5.2v32.46zm27.733-7.687c0 .472-.387.855-.866.855h-13c-.48 0-.867-.383-.867-.855v-10.25c0-.47.387-.854.867-.854H20.8v-2.137c0-2.59 2.14-4.698 4.767-4.698 2.627 0 4.766 2.107 4.766 4.698v2.135h1.734c.48 0 .866.382.866.853v10.25z"></path>
                                <path d="M25.567 11.104c-1.673 0-3.034 1.34-3.034 2.99v2.135H28.6v-2.137c0-1.65-1.36-2.99-3.033-2.99"></path>
                            </g>
                        </svg>
                        <div class="text">
                            <span class="buy">GoDaddy ®</span>
                            <span class="safety">
                                <a id="chk-godaddy" href="" target="_blank" rel="noopener noreferrer">
                                    <span>Verificado e protegido</span>
                                </a>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
  
<!--BlueSnap Hosted Payment Fields JavaScript file-->
<script type="text/javascript" src="https://sandbox.bluesnap.com/services/hosted-payment-fields/v1.0/bluesnap.hpf.mini.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script type="text/javascript" src="/script.js"></script>

</body>
</html>
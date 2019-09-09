<?php
    
tokenizar(
'4277539369335836',
'1120',
'523',
'Teste Ahoooho'
);

$_apiKey = 'ak_test_fvXB0SgOCv5fZ5fWFEDx0eV5nK7ok1';

$_correspondenciaErros = [
    '0000' => 200,
    '1000' => 503,
    '1001' => 501, 
    '1002' => 502,
    '1003' => 502,
    '1004' => 502,
    '1005' => 502,
    '1006' => 502,
    '1007' => 502,
    '1008' => 502,
    '1009' => 502,
    '1010' => 502,
    '1011' => 502,
    '1012' => 502,
    '1013' => 502,
    '1014' => 502,
    '1015' => 502,
    '1016' => 503,
    '1017' => 503,
    '1018' => 502,
    '1019' => 502,
    '1020' => 502,
    '1021' => 502,
    '1022' => 502,
    '1023' => 502,
    '1024' => 502,
    '1025' => 502,
    '1042' => 502,
    '1045' => 503,
    '2000' => 502,
    '2001' => 502,
    '2002' => 502,
    '2003' => 502,
    '2004' => 502, 
    '2005' => 502,
    '2006' => 502,
    '2007' => 502,
    '2008' => 502,
    '2009' => 502,
    '9102' => 502,
    '9108' => 503,
    '9109' => 503,
    '9111' => 503,
    '9112' => 503, 
    '9999' => 503,
];

/**
 * @param float $valor Valor a ser cobrado pelo pedido (já deve incluir valores de frete, embrulho e custos extras). Esse valor é o que será debitado do consumidor.
 * @param string $token Token que deve ser utilizado em substituição aos dados do cartão para uma autorização direta ou uma transação recorrente. Não é permitido o envio do token junto com os dados do cartão na mesma transação.     
 * 
 * @return array;
 * 
 */
function pagar($valor, $token = '', $numeroCartao = '', $numeroParcelas = 0)
{  
    $valor= number_format($valor, 2, '', ''); //trata o valor

    // $cartaoTruncado = Superlogica_String::truncarCartao($numeroCartao);     

    $transaction  = '{
        "api_key" : "ak_test_fvXB0SgOCv5fZ5fWFEDx0eV5nK7ok1",
        "amount" : "'.$valor.'",
        "card_id" : "'.$token.'",
        "installments" : "'.($numeroParcelas == 0 ? 1 : $numeroParcelas).'"}';

    $resposta= _send($transaction); 
    print_r($resposta);die(" oi");
    $result['envio_pagamento']= $transaction;
    $result['resposta_pagamento']= $resposta;
    $result['url'] = 'https://api.pagar.me/1/';
    
    $resposta = json_decode($resposta);
    
    $result['tid']= $resposta->tid;
    $result['capturada'] = $resposta->status == 'paid';
    $result['codigo-token'] = '';
    $result['tid_conciliacao']= $resposta->tid;
    $result['bandeira'] = $resposta->card->brand;
    
    if ($result['capturada']){
        $result['autorizacao']= $resposta->authorization_code;
    } else {
        $resposta->acquirer_response_code = $resposta->acquirer_response_code == '00' ? 9999 : $resposta->acquirer_response_code;
    }
    
    $result['msg']= $resposta->acquirer_response_code == '00' ? 'Sucesso.' : 'Autorização negada, motivo: ' . ($resposta->acquirer_response_code ? $resposta->acquirer_response_code : 'Cartão inválido, verifique os dados cadastrados.'); 
    $result['statuscartao'] = $result['capturada'] ? 200 : $resposta->acquirer_response_code;
    // $result['statuscartao'] = $result['capturada'] ? 200 : $this->getStatusCartao($resposta->acquirer_response_code);

    return $result;

}  

function cancelar($tid,$dtTransacao='', $valor='', $iniId='', $bandeira='', $tipoTransacao = '')
{
            
    $cancelar= '{
                "api_key": "' . $this->_password . '"
                }';
    
    $resposta= $this->_send($cancelar, 'transactions/'. $tid . '/refund');    
    
    $result['envio_cancelamento']= $cancelar;
    $result['resposta_cancelamento']= $resposta;
    $result['url'] = $this->_urls[$this->_ambiente];
    
    $resposta = json_decode($resposta);

    $result['cancelada'] = $resposta->acquirer_response_code == '00';
    $result['msg'] = $resposta->acquirer_response_code == '00' ? 'Sucesso.' : $resposta->acquirer_response_code;
    
    return $result;        
}

function tokenizar($numeroCartao='',$vencimentoCartao='',$cvv='', $nomeCartao='')
{
    $nomeCartao = substr($nomeCartao, 0, 26);
    $card = '{
            "api_key":"ak_test_fvXB0SgOCv5fZ5fWFEDx0eV5nK7ok1",
            "card_number":"'.$numeroCartao.'",
            "card_holder_name":"'.$nomeCartao.'", 
            "card_expiration_date": "'.$vencimentoCartao.'",
            "card_cvv":"'.$cvv.'"
        }';

    $cadastrarCartao = _send($card,'cards');
    $dadosCartaoPagarMe = json_decode($cadastrarCartao);

    pagar(
        '10',
        $dadosCartaoPagarMe->id,
        '4277539369335836',
        2
    );
}

/**
 * Envia uma requisição para a cielo através do xml passado
 * 
 * @param string $xml conteudo xml
 * @return array 
 */
function _send($json, $funcao='transactions')
{        
    $curl = curl_init();

    if (is_resource($curl)){
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/json'
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.pagar.me/1/'. $funcao,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $json));

        $result = curl_exec($curl);

        return $result;      
    }
}

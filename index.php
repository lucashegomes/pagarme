<?php
        print_r("po");die();

class Gateway_Adapter_Pagarme
{
    protected $_urls = [
            'homologacao'=>'https://api.pagar.me/1/',
            'producao'=> 'https://api.pagar.me/1/'        
    ];   

    protected $_correspondenciaErros= array
    (
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
    );

    public function teste() {
        print_r("po");die();
    }

    /**
     * @param float $valor Valor a ser cobrado pelo pedido (já deve incluir valores de frete, embrulho e custos extras). Esse valor é o que será debitado do consumidor.
     * @param $descricao Descrição da transação.
     * @param string $token Token que deve ser utilizado em substituição aos dados do cartão para uma autorização direta ou uma transação recorrente. Não é permitido o envio do token junto com os dados do cartão na mesma transação.     
     * 
     * @return array;
     * 
     */
    public function pagar($valor, $descricao, $bandeira = '', $token = '', $numeroCartao = '', $vencimentoCartao = '', $codVerificacaoCartao = '', $urlRetorno = '', $idCliente = '', $nomeCartao = '', $emailCliente = '', $idCobranca = '', $numeroParcelas = 0, $antiFraude = [], $conta = [])
    {  // antiga função _autorizar
        $agora = Superlogica_Date::now();
        $id = str_replace(array('.',','),'',microtime(true));//uniqid não funciona para este caso
        $valor= number_format($valor, 2, '', ''); //trata o valor

        $mesAno = new Superlogica_Date('01/' . $vencimentoCartao, 'd/m/y');
        
        $cartaoTruncado = Superlogica_String::truncarCartao($numeroCartao); 
        
        if(($antiFraude['CPF']) && ($antiFraude['DDD']) && ($antiFraude['TELEFONE']) && ($antiFraude['ENDERECO']) && ($antiFraude['NUMERO']) && ($antiFraude['BAIRRO']) && ($antiFraude['CEP'])){
                $transaction  = '{
                                "api_key" : "'.$this->_password.'",
                                "amount" : "'.$valor.'",
                                "card_id" : "'.$token.'",
                                "installments" : "'.($numeroParcelas == 0 ? 1 : $numeroParcelas).'",
                                "customer" : {
                                                "name" : "'.$nomeCartao.'",
                                                "email" : "'.$emailCliente.'",
                                                "document_number" : "'.$antiFraude['CPF'].'",
                                                "phone" : {
                                                            "ddd" : "'.$antiFraude['DDD'].'",
                                                            "number" : "'.$antiFraude['TELEFONE'].'"
                                                           },
                                                "address" : {
                                                    "street" : "'.$antiFraude['ENDERECO'].'",
                                                    "street_number": "'.$antiFraude['NUMERO'].'",
                                                    "neighborhood" : "'.$antiFraude['BAIRRO'].'",
                                                    "zipcode" : "'.str_replace('-', '', $antiFraude['CEP']).'"
                                                }
                                }
                            }';
        }else{
                   $transaction  = '{
                        "api_key" : "'.$this->_password.'",
                        "amount" : "'.$valor.'",
                        "card_id" : "'.$token.'",
                        "installments" : "'.($numeroParcelas == 0 ? 1 : $numeroParcelas).'"}';
        }

        $resposta= $this->_send($transaction); 
        
        $result['envio_pagamento']= $transaction;
        $result['resposta_pagamento']= $resposta;
        $result['url'] = $this->_urls[$this->_ambiente];
        
        $resposta = json_decode($resposta);
        
        $result['tid']= $resposta->tid;
        $result['capturada'] = $resposta->status == 'paid';
        $result['codigo-token'] = '';
        $result['tid_conciliacao']= $resposta->tid;
        $result['bandeira'] = $resposta->card->brand;
        
        if ($result['capturada']){
            $result['autorizacao']= $resposta->authorization_code;
            $result['cartao_truncado']= $cartaoTruncado;
        } else {
            $resposta->acquirer_response_code = $resposta->acquirer_response_code == '00' ? 9999 : $resposta->acquirer_response_code;
        }
        
        $result['msg']= $resposta->acquirer_response_code == '00' ? 'Sucesso.' : 'Autorização negada, motivo: ' . ($resposta->acquirer_response_code ? $resposta->acquirer_response_code : 'Cartão inválido, verifique os dados cadastrados.'); 
        $result['statuscartao'] = $result['capturada'] ? 200 : $resposta->acquirer_response_code;
        $result['statuscartao'] = $result['capturada'] ? 200 : $this->getStatusCartao($resposta->acquirer_response_code);

        return $result;

    }  

    public function cancelar($tid,$dtTransacao='', $valor='', $iniId='', $bandeira='', $tipoTransacao = '')
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
    
    /**
     * Envia uma requisição para a cielo através do xml passado
     * 
     * @param string $xml conteudo xml
     * @return array 
     */
    protected function _send($json, $funcao='transactions')
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
                    CURLOPT_URL => $this->_urls[$this->_ambiente]. $funcao,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $json));

            $result = curl_exec($curl);        
            
            return $result;      
        }
    }
    
    public function tokenizar($numeroCartao='',$vencimentoCartao='',$codVerificacaoCartao='', $nomeCartao='')
    {
        $mesAno = new Superlogica_Date('01/' . $vencimentoCartao, 'd/m/y');
        $nomeCartao = substr($nomeCartao, 0, 26);
        $card = '{
             "api_key":"'.$this->_password.'",
             "card_number":"'.$numeroCartao.'",
             "card_holder_name":"'.$nomeCartao.'", 
             "card_expiration_date": "'.$mesAno->toString('my').'",
             "card_cvv":"'.$codVerificacaoCartao.'"
         }';

        $cadastrarCartao = $this->_send($card,'cards');
        $dadosCartaoPagarMe = json_decode($cadastrarCartao);
        
        if( !$dadosCartaoPagarMe->id ){
            Helpers_Log::set( print_r($cadastrarCartao,true) );
        }

        return $dadosCartaoPagarMe->id;
    }

}

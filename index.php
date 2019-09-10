<?php

use Aws\CloudFront\Exception\Exception;

require("vendor/autoload.php");

$pagarme = new Gateway_Pagarme();
$pagarme->pay();

class Gateway_Pagarme {

    private $_apiKey = 'ak_test_fvXB0SgOCv5fZ5fWFEDx0eV5nK7ok1';
    private $_paymentWay = '';
    private $_pagarme = null;
    private $_data = [];

    public function __construct($paymentWay = 'credit_card')
    {
        $this->_paymentWay = $paymentWay;
        $this->_pagarme = new PagarMe\Client($this->_apiKey);
        $this->_data = $_POST;
    }

    /**
     * @param float $valor Valor a ser cobrado pelo pedido (já deve incluir valores de frete, embrulho e custos extras). Esse valor é o que será debitado do consumidor.
     * @param string $token Token que deve ser utilizado em substituição aos dados do cartão para uma autorização direta ou uma transação recorrente. Não é permitido o envio do token junto com os dados do cartão na mesma transação.     
     * 
     * @return array;
     * 
     */
    public function pay()
    {
        try {

            if ($this->_paymentWay == 'credit_card') {
    
                $transaction = $this->_pagarme->transactions()->create([
                    'amount' => $this->_data['amount'],
                    'payment_method' => $this->_paymentWay,
                    'card_holder_name' => $this->_data['cardholder-name'],
                    'card_cvv' => $this->_data['cvv'],
                    'card_number' => $this->_data['card-number'],
                    'card_expiration_date' => $this->_data['exp-date'],
                    'customer' => [
                        'external_id' => $this->_data['client_id'], //VALIDAR NOME CORRETO DO ID NA BASE DO CLIENTE
                        'name' => $this->_data['full-name'],
                        'type' => count($this->_data['cpf-cnpj']) <= 11 ? 'individual' : 'corporation', //VALIDAR ISSO
                        'country' => 'br',
                        'documents' => [
                          [
                            'type' => 'cpf',
                            'number' => $this->_data['cpf-cnpj'],
                          ]
                        ],
                        'phone_numbers' => [ $this->_data['cellphone-number'] ],
                        'email' => $this->_data['email'],
                    ],
                    'billing' => [
                        'name' => $this->_data['cardholder-name'],
                        'address' => [
                          'country' => 'br',
                          'street' => 'Avenida Brigadeiro Faria Lima',
                          'street_number' => '1811',
                          'state' => 'sp',
                          'city' => 'Sao Paulo',
                          'neighborhood' => 'Jardim Paulistano',
                          'zipcode' => '01451001'
                        ]
                    ],
                    'shipping' => [
                        'name' => 'Nome de quem receberá o produto',
                        'fee' => 1020,
                        'delivery_date' => '2019-09-10',
                        'expedited' => false,
                        'address' => [
                          'country' => 'br',
                          'street' => 'Avenida Brigadeiro Faria Lima',
                          'street_number' => '1811',
                          'state' => 'sp',
                          'city' => 'Sao Paulo',
                          'neighborhood' => 'Jardim Paulistano',
                          'zipcode' => '01451001'
                        ]
                    ],
                    'items' => [
                        [
                          'id' => '1',
                          'title' => 'R2D2',
                          'unit_price' => 300,
                          'quantity' => 1,
                          'tangible' => true
                        ],
                        [
                          'id' => '2',
                          'title' => 'C-3PO',
                          'unit_price' => 700,
                          'quantity' => 1,
                          'tangible' => true
                        ]
                    ]
                ]);
            
            } else if ($this->_paymentWay == 'boleto') {
    
            }
        
        } catch (Exception $e) {
            throw new Exception('Erro ao realizar pagamento: ', $e);
        }
    }

    public function getTransaction($idTransaction = '')
    {
        if (empty($idTransaction)) {
            throw new Exception('Identificador da transação não informado');
        }

        try {

            $transaction = $this->_pagarme->transactions()->get([
                'id' => $idTransaction
            ]);

        } catch(Exception $e) {
            throw new Exception("Erro ao consultar a transação {$idTransaction}: ", $e);
        }

        return $transaction;
    }

    public function sendNotification($idTransaction = '', $email = '')
    {
        if (empty($email)) {
            return false;
        }

        $transactionPaymentNotify = $this->_pagarme->transactions()->collectPayment([
            'id' => $idTransaction,
            'email' => $email,
        ]);

        return $transactionPaymentNotify;
    }
}

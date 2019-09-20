<?php

use Aws\CloudFront\Exception\Exception;

use function GuzzleHttp\json_decode;

require("vendor/autoload.php");

if (isset($_POST['btn-boleto'])) {
    $pagarme = new Gateway_Pagarme('boleto');
    $pagarme->payTransaction();

} else if (isset($_POST['btn-paypal'])) {
    echo "PAYPAL REDIRECT HERE";
    
} else {
    $pagarme = new Gateway_Pagarme('credit_card');
    $pagarme->payTransaction();
}

class Gateway_Pagarme {

    private $_apiKey = 'ak_test_fvXB0SgOCv5fZ5fWFEDx0eV5nK7ok1';
    private $_paymentMethod = null;
    private $_pagarme = null;
    private $_data = [];

    /**
     * Endpoint para receber notificações de atualizações da transação
     *
     * @var string
     */
    private $_postback = '';

    /**
     * Texto (de até 13 caracteres, somente letras e números) que aparecerá na fatura do cartão
     *
     * @var string
     */
    private $_softDescription = '';
    
    /**
     * Parâmetros opcionais de boleto para enviar na requisição
     *
     * @var string
     */
    private $_boletoInstructions = '';
    private $_boletoExpirationDate = ''; // Data de vencimento do boleto

    public function __construct($paymentMethod = 'credit_card')
    {
        $this->_paymentMethod = $_POST['payment-method'] ?: $paymentMethod;
        $this->_pagarme = new PagarMe\Client($this->_apiKey);
        $this->_data = $_POST;
    }

    public function payTransaction()
    {
        try {

            $arParams = [
                'amount' => str_replace('.', '', $this->_data['amount']),
                'postback_url' => $this->_postback,
                'soft_descriptor' => substr($this->_softDescription, 0, 13),

                'customer' => [
                    'external_id' => $this->_data['client_id'],
                    'name' => $this->_data['full-name'],
                    'type' => ( strlen($this->_data['cpf-cnpj']) <= 11 ? 'individual' : 'corporation' ),
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
                    'name' => 'Nome de quem receberÃ¡ o produto',
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
            ];

            if ($this->_paymentMethod == 'credit_card') {
    
                $params = [
                    'payment_method' => $this->_paymentMethod,
                    'card_holder_name' => $this->_data['cardholder-name'],
                    'card_cvv' => $this->_data['cvv'],
                    'card_number' => $this->_data['card-number'],
                    'card_expiration_date' => $this->_data['exp-date'],
                ];

                $params = array_merge($params, $arParams);

                $transaction = $this->_pagarme->transactions()->create($params);

                $result = $this->_toArray($transaction);

            } else if ($this->_paymentMethod == 'boleto') {

                if (!empty($arParams['customer']['name'])) {
                    echo "Nome do cliente não informado.";    
                }

                if (!empty($arParams['customer']['documents'])) {
                    echo "Documentos do cliente não informado.";
                }

                $params = [
                    'boleto_instructions' => substr($this->boletoInstructions, 0, 255),
                    'boleto_expiration_date' => $this->getBoletoExpirationDate(),
                ];

                $params = array_merge($params, $arParams);

                $transaction = $this->_pagarme->transactions()->create($params);

                $result = $this->_toArray($transaction);
            }

            print_r($result);

        } catch (Exception $e) {
            throw new Exception('Erro ao realizar pagamento: ', $e);
        }

        return $result;
    }

    public function cancelTransaction($idTransaction = null)
    {
        if (empty($idTransaction)) {
            throw new Exception('Identificador da transação não informado');
        }

        try {

            $refundedTransaction = $this->_pagarme->transactions()->refund([
                'id' => $idTransaction,
            ]);

            $result = $this->_toArray($refundedTransaction);
            
        } catch (Exception $e) {
            throw new Exception("Erro ao cancelar a transação {$idTransaction}: ", $e);
        }

        return $result;
    }

    public function getTransaction($idTransaction = null)
    {
        if (empty($idTransaction)) {
            throw new Exception('Identificador da transação não informado');
        }

        try {

            $transaction = $this->_pagarme->transactions()->get([
                'id' => $idTransaction,
            ]);

            $result = $this->_toArray($transaction);
            
        } catch(Exception $e) {
            throw new Exception("Erro ao consultar a transação {$idTransaction}: ", $e);
        }

        return $result;
    }

    public function getCancellations($idTransaction)
    {
        $transactionRefunds = $this->_pagarme->refunds()->getList( !empty($idTransaction) ? [
            'transaction_id' => $idTransaction
        ] : []);

        $result = $this->_toArray($transactionRefunds);

        return $result;
    }

    public function calculateRate($amount, $freeInstallments, $maxInstallments, $rate)
    {
        $calculateInstallments = $this->_pagarme->transactions()->calculateInstallments([
            'amount' => $amount,
            'free_installments' => $freeInstallments,
            'max_installments' => $maxInstallments,
            'interest_rate' => $rate,
        ]);

        $result = $this->_toArray($calculateInstallments);

        return $result;
    }

    public function sendNotification($idTransaction = null, $email = null)
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

    public function setBoletoExpirationDate($days = 5)
    {
        $date = date('Y-m-d', strtotime( date("Y-m-d", strtotime("now") ) . " +$days days"));
        $this->_boletoExpirationDate = $date;

        return $this->_boletoExpirationDate;
    }

    public function getBoletoExpirationDate()
    {
        if ( empty($this->_boletoExpirationDate) ) {
            $this->setBoletoExpirationDate();
        }

        return $this->_boletoExpirationDate;
    }

    private function _toArray($object = [])
    {
        return json_decode(json_encode($object), true);
    }
}

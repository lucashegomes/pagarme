<?php

use function GuzzleHttp\json_decode;

require("vendor/autoload.php");

if (isset($_POST['btn-boleto'])) {
    $pagarme = new Gateway_Pagarme('boleto');
    $pagarme->payTransaction();
    // $pagarme->createPlan();
    // $pagarme->getPlans(435350);
    // $pagarme->createSubscription(435350);

} else if (isset($_POST['btn-paypal'])) {
    echo "PAYPAL REDIRECT HERE";
    
} else {
    $pagarme = new Gateway_Pagarme('credit_card');
    $pagarme->payTransaction();
    // $pagarme->createPlan();
    // $pagarme->getPlans(435350);
    // $pagarme->createSubscription(435350);
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
    private $_postback = 'https://gdigital.com.br/postback';

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
        $this->_paymentMethod = $paymentMethod;
        $this->_pagarme = new PagarMe\Client($this->_apiKey);
        $this->_data = $_POST;
    }

    public function payTransaction()
    {
        $result = [];

        try {

            $cpfCnpj = $this->_formatStringToAlphanumeric($this->_data['cpf-cnpj']);

            $arParams = [
                'payment_method' => $this->_paymentMethod,
                'async' => false,
                'amount' => str_replace('.', '', $this->_data['amount']),
                'postback_url' => $this->_postback,
                'soft_descriptor' => substr($this->_softDescription, 0, 13),

                'customer' => [
                    'external_id' => !empty($this->_data['client_id']) ? $this->_data['client_id'] : '1', //FAZER TRATAMENTO MELHOR
                    'name' => $this->_data['full-name'],
                    'type' => ( strlen($cpfCnpj) <= 11 ? 'individual' : 'corporation' ),
                    'country' => 'br',
                    'documents' => [
                      [
                        'type' => 'cpf',
                        'number' => $cpfCnpj,
                      ]
                    ],
                    'phone_numbers' => [ '+55' . $this->_formatStringToAlphanumeric($this->_data['cellphone-number']) ],
                    'email' => $this->_data['email'],
                ],
                
                'billing' => [
                    'name' => $this->_data['cardholder-name'] ?: $this->_data['full-name'],
                    'address' => [
                      'country' => 'br',
                      'street' => $this->_data['address'],
                      'street_number' => $this->_data['address-number'],
                      'state' => $this->_data['address-state'],
                      'city' => $this->_data['address-city'],
                      'neighborhood' => $this->_data['address-neighborhood'],
                      'zipcode' => $this->_formatStringToAlphanumeric($this->_data['address-cep']),
                    ]
                ],

                'items' => $this->_setCompositionItems(),
            ];

            if ($this->_paymentMethod == 'credit_card') {

                $params = [
                    'installments' => (!empty($this->_data['select-installments'])) ? $this->_data['select-installments'] : 1,
                    'card_holder_name' => $this->_data['cardholder-name'],
                    'card_cvv' => $this->_data['cvv'],
                    'card_number' => $this->_data['card-number'],
                    'card_expiration_date' => $this->_formatStringToAlphanumeric($this->_data['exp-date']),
                ];

                $params = array_merge($params, $arParams);

                $transaction = $this->_pagarme->transactions()->create($params);

                $result [] = $this->_toArray($transaction);

            } else if ($this->_paymentMethod == 'boleto') {

                $params = [
                    'installments' => 1,
                    'boleto_instructions' => substr($this->_boletoInstructions, 0, 255),
                    'boleto_expiration_date' => $this->getBoletoExpirationDate(),
                ];

                $params = array_merge($params, $arParams);

                $transaction = $this->_pagarme->transactions()->create($params);

                $result [] = $this->_toArray($transaction);
            }

        } catch (\Exception $e) {
            $result [] = 'Erro ao realizar pagamento: ' . $e;
        }

        echo '<pre>';
        print_r($result);
        echo '</pre>';
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
            
        } catch (\Exception $e) {
            throw new Exception("Erro ao cancelar a transação {$idTransaction}: ", $e);
        }

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    public function createPlan($paymentMethods = 'credit_card', $amount = 1, $days = 30, $installments = 12, $name = 'Plano')
    {
        $charges = in_array('credit_card', (array) $paymentMethods) ? 2 : 3;
        
        if ($this->_recurrence) {
            $charges = null;
        }

        $plan = $this->_pagarme->plans()->create([
            'amount' => $amount,
            'days' => $days,
            'name' => $name,
            'payment-methods' => $paymentMethods,
            'charges' => $charges,
            'installments' => $charges == 2 ? $installments : 1,
        ]);

        $result = $this->_toArray($plan);

        $this->createSubscription($result[0]['id']);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    public function createSubscription($idPlan = '', $paymentMethod = ['credit_card'])
    {
        if (!$idPlan) return;

        $cpfCnpj = $this->_formatStringToAlphanumeric($this->_data['cpf-cnpj']);

        $subscription = $this->_pagarme->subscriptions()->create([
            'plan_id' => $idPlan,
            'payment_method' => $paymentMethod,
            'card_holder_name' => $this->_data['cardholder-name'],
            'card_cvv' => $this->_data['cvv'],
            'card_number' => $this->_data['card-number'],
            'card_expiration_date' => $this->_data['exp-date'],
            'postback_url' => $this->_postback,
            'customer' => [
                'email' => $this->_data['email'],
                'name' => $this->_data['cardholder-name'] ?: $this->_data['full-name'],
                'document_number' => $cpfCnpj,
            ],
        ]);

        $result = $this->_toArray($subscription);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }


    public function updatePlan($idPlan = '', $name = '')
    {
        if (!$idPlan) return;

        $updatedPlan = $this->_pagarme->plans()->update([
            'id' => $idPlan,
            'name' => $name,
        ]);
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
            
        } catch(\Exception $e) {
            throw new Exception("Erro ao consultar a transação {$idTransaction}: ", $e);
        }

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    public function getCancellations($idTransaction)
    {
        $transactionRefunds = $this->_pagarme->refunds()->getList( !empty($idTransaction) ? [
            'transaction_id' => $idTransaction
        ] : []);

        $result = $this->_toArray($transactionRefunds);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    public function getCustomer($idCustomer)
    {
        if (!$idCustomer) return;

        $customer = $this->_pagarme->customers($idCustomer);

        $result = $this->_toArray($customer);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    public function getPlans($idPlan, $amount = null, $days = null, $name = null)
    {
        $plan = $this->_pagarme->plans()->getList([
            'id' => $idPlan,
            'amount' => $amount,
            'days' => $days,
            'name' => $name,
        ]);

        $result = $this->_toArray($plan);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    public function calculateRate($amount, $rate = 1.531, $maxInstallments = 12)
    {
        for ($i = 1; $i <= $maxInstallments; $i++) {
            
            $installments[$i] = $amount;

            if ($i > 1) {
                $installments[$i] = ($amount * pow(1 + ($rate / 100), $i)) / $i;
            }
        }

        return $installments;
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

    public function boletoPayTesting($idTransaction)
    {
        $paidBoleto = $this->_pagarme->transactions()->simulateStatus([
            'id' => $idTransaction ?: '6993906',
            'status' => 'paid'
        ]);

        $result = $this->_toArray($paidBoleto);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

    private function _setCompositionItems()
    {
        $arItems = $this->_data['items'];
        $result = [];

        foreach ($arItems as $item) {
            $result [] = [
                'id' => $item['product-id'],
                'title' => $item['product-name'],
                'unit_price' => $this->_formatStringToAlphanumeric($item['unit-price']),
                'quantity' => $item['quantity'],
                'tangible' => true
            ];
            
        }

        return $result;
    }

    private function _toArray($object = [])
    {
        return json_decode(json_encode($object), true);
    }

    private function _formatStringToAlphanumeric($string = '')
    {
        $result = preg_replace("/[^a-zA-Z0-9]+/", "", $string);
        return $result;
    }
}

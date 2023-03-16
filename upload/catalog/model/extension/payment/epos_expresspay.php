<?php
class ModelExtensionPaymentEposExpressPay extends Model{
    const NAME_PAYMENT_METHOD                       = 'payment_epos_expresspay_name_payment_method';
    const SORT_ORDER_PARAM_NAME                     = 'payment_epos_expresspay_sort_order';
    const CURRENCY = 933;
    const RETURN_TYPE = 'redirect';
    
    private static $model;

    public function __construct($registry)
    {
        parent::__construct($registry);
    }

    public function getMethod($address, $total)
    {
        $this->load->language('extension/payment/epos_expresspay');
        $status = false;

        if ($total > 0) {
            $status = true;
        }

        $method_data = array();

        $code = 'epos_expresspay';
        
        // Название метода оплаты
        $textTitle = $this->language->get('heading_title');
        if($this->config->get(self::NAME_PAYMENT_METHOD) !== null){
            $textTitle = $this->config->get(self::NAME_PAYMENT_METHOD);
        }
        
        $sortOrder = $this->config->get(self::SORT_ORDER_PARAM_NAME);

        if ($status) {
            $method_data = array(
                'code'       => $code,
                'title'      => $textTitle,
                'terms'      => '',
                'sort_order' => $sortOrder
            );
        }

        return $method_data;
    }

    public function setParams($data, $config)
    {
        self::$model = new EposExpressPayModel($config);
        $orderId = $this->session->data['order_id'];
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($orderId);
        $amount = str_replace('.', ',', $this->currency->format($order_info['total'], $this->session->data['currency'], '', false));
        if ($this->session->data['currency'] !== "BYN") {
            $response = $this->getCurrencyRateFromNBRB($this->session->data['currency']);            
            $CurOfficialRate = $response->Cur_OfficialRate;
            $amount = str_replace('.', ',', round($amount * $CurOfficialRate, 2));
        }

        //Обрезать +
        //заменить знаки (' ', '-','(',')') на пустую строку
        //посчитать количество символов
        //Если номер не прошел проверку, не использовать
        $smsPhone = $order_info['telephone'];

        $smsPhone = str_replace('+', '', $smsPhone);
        $smsPhone = str_replace(' ', '', $smsPhone);
        $smsPhone = str_replace('-', '', $smsPhone);
        $smsPhone = str_replace('(', '', $smsPhone);
        $smsPhone = str_replace(')', '', $smsPhone);

        $signatureParams['Token'] = self::$model->getToken();
        $signatureParams['ServiceId'] = self::$model->getServiceId();
        $signatureParams['AccountNo'] = $orderId;
        $signatureParams['Amount'] = $amount;
        $signatureParams['Currency'] = self::CURRENCY;
        $signatureParams['Info'] = str_replace('##order_id##', $orderId, self::$model->getInfo());
        $signatureParams['Surname'] = $order_info['lastname'];
        $signatureParams['FirstName'] = $order_info['firstname'];
        $signatureParams['City'] = $order_info['payment_city'];
        $signatureParams['IsNameEditable'] = self::$model->getIsNameEdit();
        $signatureParams['IsAmountEditable'] = self::$model->getIsAmountEdit();
        $signatureParams['IsAddressEditable'] = self::$model->getIsAddressEdit();
        $signatureParams['EmailNotification'] = $order_info['email'];
        $signatureParams['SmsPhone'] = $smsPhone;
        $signatureParams['ReturnType'] = self::RETURN_TYPE;
        $signatureParams['ReturnUrl'] = $this->url->link('extension/payment/epos_expresspay/success');
        $signatureParams['FailUrl'] = $this->url->link('extension/payment/epos_expresspay/fail');
        $signatureParams["ReturnInvoiceUrl"] = "1";

        $data['Signature'] = self::computeSignature($signatureParams, self::$model->getSecretWord(), 'add-web-invoice');
        unset($signatureParams['Token']);
        $data = array_merge($data, $signatureParams);

        $data['Action'] = self::$model->getActionUrl();

        return $data;
    }

    public function getQrbase64($invoiceId, $config)
    {
        self::$model = new EposExpressPayModel($config);
        $signatureParams = array(
            "Token" => self::$model->getToken(),
            "InvoiceId" => $invoiceId,
            "ViewType" => "base64",
            "ImageWidth" => "",
            "ImageHeight" => ""
        );
        $signatureParams['Signature'] = self::computeSignature($signatureParams, self::$model->getSecretWord(), 'get-qr-code');

        return self::sendRequest(self::$model->getQrCodeUrl() . http_build_query($signatureParams));
    }

    private function getCurrencyRateFromNBRB($currency)
    {
        return json_decode(file_get_contents("https://www.nbrb.by/api/exrates/rates/$currency?parammode=2"));
    }
    
    private function sendRequest($url) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * 
     * Формирование цифровой подписи
     * 
     * @param array  $signatureParams Список передаваемых параметров
     * @param string $secretWord      Секретное слово
     * @param string $method          Метод формирования цифровой подписи
     * 
     * @return string $hash           Сформированная цифровая подпись
     * 
     */
    private static function computeSignature($signatureParams, $secretWord, $method)
    {
        $normalizedParams = array_change_key_case($signatureParams, CASE_LOWER);
        $mapping = array(
            "get-qr-code"          => array(
                "token",
                "invoiceid",
                "viewtype",
                "imagewidth",
                "imageheight"
            ),
            "add-web-invoice"      => array(
                "token",
                "serviceid",
                "accountno",
                "amount",
                "currency",
                "expiration",
                "info",
                "surname",
                "firstname",
                "patronymic",
                "city",
                "street",
                "house",
                "building",
                "apartment",
                "isnameeditable",
                "isaddresseditable",
                "isamounteditable",
                "emailnotification",
                "smsphone",
                "returntype",
                "returnurl",
                "failurl",
                "returninvoiceurl"
            ),
            "add-webcard-invoice" => array(
                "token",
                "serviceid",
                "accountno",
                "expiration",
                "amount",
                "currency",
                "info",
                "returnurl",
                "failurl",
                "language",
                "sessiontimeoutsecs",
                "expirationdate",
                "returntype",
                "returninvoiceurl"
            )
        );
        $apiMethod = $mapping[$method];
        $result = "";
        foreach ($apiMethod as $item) {
            $result .= (isset($normalizedParams[$item])) ? $normalizedParams[$item] : '';
        }
        $hash = strtoupper(hash_hmac('sha1', $result, $secretWord));
        return $hash;
    }
}

class EposExpressPayModel
{
    const TOKEN_PARAM_NAME                          = 'payment_epos_expresspay_token';
    const SERVICE_ID_PARAM_NAME                     = 'payment_epos_expresspay_service_id';
    const SECRET_WORD_PARAM_NAME                    = 'payment_epos_expresspay_secret_word';
    const IS_NAME_EDIT_PARAM_NAME                   = 'payment_epos_expresspay_is_name_editable';
    const IS_AMOUNT_EDIT_PARAM_NAME                 = 'payment_epos_expresspay_is_amount_editable';
    const IS_ADDRESS_EDIT_PARAM_NAME                = 'payment_epos_expresspay_is_address_editable';
    const IS_TEST_MODE_PARAM_NAME                   = 'payment_epos_expresspay_is_test_mode';
    const API_URL_PARAM_NAME                        = 'payment_epos_expresspay_api_url';
    const SANDBOX_URL_PARAM_NAME                    = 'payment_epos_expresspay_sandbox_url';
    const INFO_PARAM_NAME                           = 'payment_epos_expresspay_info';

    private $token;
    private $serviceId;
    private $secretWord;
    private $isNameEdit;
    private $isAmountEdit;
    private $isAddressEdit;
    private $isTestMode;
    private $apiUrl;
    private $sandboxUrl;
    private $info;

    public function __construct($config)
    {
        if ($config == null)
            return;

        $this->setToken($config->get(self::TOKEN_PARAM_NAME));
        $this->setServiceId($config->get(self::SERVICE_ID_PARAM_NAME));
        $this->setSecretWord($config->get(self::SECRET_WORD_PARAM_NAME));
        $this->setIsNameEdit($config->get(self::IS_NAME_EDIT_PARAM_NAME));
        $this->setIsAmountEdit($config->get(self::IS_AMOUNT_EDIT_PARAM_NAME));
        $this->setIsAddressEdit($config->get(self::IS_ADDRESS_EDIT_PARAM_NAME));
        $this->setIsTestMode($config->get(self::IS_TEST_MODE_PARAM_NAME));
        $this->setApiUrl($config->get(self::API_URL_PARAM_NAME));
        $this->setSandboxUrl($config->get(self::SANDBOX_URL_PARAM_NAME));
        $this->setInfo($config->get(self::INFO_PARAM_NAME));
    }

    public function getActionUrl()
    {
        if ($this->isTestMode) {
            return $this->sandboxUrl.'/web_invoices';
        } else {
            return $this->apiUrl.'/web_invoices';
        }
    }

    public function getQrCodeUrl()
    {
        if ($this->isTestMode) {
            return $this->sandboxUrl.'/qrcode/getqrcode?';
        } else {
            return $this->apiUrl.'/qrcode/getqrcode?';
        }
    }

    public function getToken()
    {
        return $this->token;
    }

    private function setToken($token)
    {
        $this->token = $token;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    private function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function getSecretWord()
    {
        return $this->secretWord;
    }

    private function setSecretWord($secretWord)
    {
        $this->secretWord = $secretWord;
    }

    public function getIsNameEdit()
    {
        return $this->isNameEdit;
    }

    private function setIsNameEdit($isNameEdit)
    {
        $checkboxValue = $this->normCheckboxValue($isNameEdit);
        $this->isNameEdit = $checkboxValue;
    }

    public function getIsAmountEdit()
    {
        return $this->isAmountEdit;
    }

    private function setIsAmountEdit($isAmountEdit)
    {
        $checkboxValue = $this->normCheckboxValue($isAmountEdit);
        $this->isAmountEdit = $checkboxValue;
    }

    public function getIsAddressEdit()
    {
        return $this->isAddressEdit;
    }

    private function setIsAddressEdit($isAddressEdit)
    {
        $checkboxValue = $this->normCheckboxValue($isAddressEdit);
        $this->isAddressEdit = $checkboxValue;
    }

    private function setIsTestMode($isTestMode)
    {
        $checkboxValue = $this->normCheckboxValue($isTestMode);
        $this->isTestMode = $checkboxValue;
    }

    private function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    private function setSandboxUrl($sandboxUrl)
    {
        $this->sandboxUrl = $sandboxUrl;
    }
    
    public function getInfo()
    {
        return $this->info;
    }

    private function setInfo($info)
    {
        $this->info = $info;
    }
    
    private function normCheckboxValue($checkboxValue)
    {
        $normValue = 0;

        if ($checkboxValue == null) {
            return $normValue;
        }

        switch ($checkboxValue) {
            case "on":
            case 1:
            case "1":
                $normValue = 1;
        }

        return $normValue;
    }
}

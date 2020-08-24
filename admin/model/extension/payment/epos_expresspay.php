<?php

class ModelExtensionPaymentEposExpresspay extends Model
{
    const TOKEN_PARAM_NAME                                  = 'payment_epos_expresspay_token';
    const SERVICE_ID_PARAM_NAME                             = 'payment_epos_expresspay_service_id';
    const SERVICE_PROVIDER_ID_PARAM_NAME                    = 'payment_epos_expresspay_service_provider_id';
    const EPOS_SERVICE_ID_PARAM_NAME                        = 'payment_epos_expresspay_epos_service_id';
    const NOTIFICATION_URL_PARAM_NAME                       = 'payment_epos_expresspay_url_notification';
    const SECRET_WORD_PARAM_NAME                            = 'payment_epos_expresspay_secret_word';
    const USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME         = 'payment_epos_expresspay_is_use_signature_for_notification';
    const IS_TEST_MODE_PARAM_NAME                           = 'payment_epos_expresspay_is_test_mode';
    const API_URL_PARAM_NAME                                = 'payment_epos_expresspay_api_url';
    const SANDBOX_URL_PARAM_NAME                            = 'payment_epos_expresspay_sandbox_url';
    const SECRET_WORD_NOTIFICATION_PARAM_NAME               = 'payment_epos_expresspay_secret_word_for_notification';
    const INFO_PARAM_NAME                                   = 'payment_epos_expresspay_info';
    const IS_NAME_EDIT_PARAM_NAME                           = 'payment_epos_expresspay_is_name_editable';
    const IS_AMOUNT_EDIT_PARAM_NAME                         = 'payment_epos_expresspay_is_amount_editable';
    const IS_ADDRESS_EDIT_PARAM_NAME                        = 'payment_epos_expresspay_is_address_editable';
    const STATUS_PARAM_NAME                                 = 'payment_epos_expresspay_status';
    const SORT_ORDER_PARAM_NAME                             = 'payment_epos_expresspay_sort_order';
    const MESSAGE_SUCCESS_PARAM_NAME                        = 'payment_epos_expresspay_message_success';
    const PROCESSED_STATUS_ID_PARAM_NAME                    = 'payment_epos_expresspay_processed_status_id';
    const FAIL_STATUS_ID_PARAM_NAME                         = 'payment_epos_expresspay_fail_status_id';
    const SUCCESS_STATUS_ID_PARAM_NAME                      = 'payment_epos_expresspay_success_status_id';


    private static $model;

    public function __construct($registry)
    {
        parent::__construct($registry);

        self::$model = new EposExpressPayModel();
    }

    public function setParametersFromConfig($config, $request, $data)
    {
        if (isset($request[self::TOKEN_PARAM_NAME])) {
            self::$model->setToken($request[self::TOKEN_PARAM_NAME]);
        } else {
            self::$model->setToken($config->get(self::TOKEN_PARAM_NAME));
        }

        if (isset($request[self::SERVICE_ID_PARAM_NAME])) {
            self::$model->setServiceId($request[self::SERVICE_ID_PARAM_NAME]);
        } else {
            self::$model->setServiceId($config->get(self::SERVICE_ID_PARAM_NAME));
        }

        if (isset($request[self::SERVICE_PROVIDER_ID_PARAM_NAME])) {
            self::$model->setServiceProviderId($request[self::SERVICE_PROVIDER_ID_PARAM_NAME]);
        } else {
            self::$model->setServiceProviderId($config->get(self::SERVICE_PROVIDER_ID_PARAM_NAME));
        }

        if (isset($request[self::EPOS_SERVICE_ID_PARAM_NAME])) {
            self::$model->setEposServiceId($request[self::EPOS_SERVICE_ID_PARAM_NAME]);
        } else {
            self::$model->setEposServiceId($config->get(self::EPOS_SERVICE_ID_PARAM_NAME));
        }

        if (isset($request[self::SECRET_WORD_PARAM_NAME])) {
            self::$model->setSecretWord($request[self::SECRET_WORD_PARAM_NAME]);
        } else {
            self::$model->setSecretWord($config->get(self::SECRET_WORD_PARAM_NAME));
        }

        if (isset($request[self::NOTIFICATION_URL_PARAM_NAME])) {
            self::$model->setNotificationUrl($request[self::NOTIFICATION_URL_PARAM_NAME]);
        } else {
            self::$model->setNotificationUrl($config->get(self::NOTIFICATION_URL_PARAM_NAME));
        }

        if (isset($request[self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME])) {
            self::$model->setUseSignatureNotification($request[self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME]);
        } else {
            self::$model->setUseSignatureNotification($config->get(self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME));
        }

        if (isset($request[self::IS_TEST_MODE_PARAM_NAME])) {
            self::$model->setIsTestMode($request[self::IS_TEST_MODE_PARAM_NAME]);
        } else {
            self::$model->setIsTestMode($config->get(self::IS_TEST_MODE_PARAM_NAME));
        }

        if (isset($request[self::API_URL_PARAM_NAME])) {
            self::$model->setApiUrl($request[self::API_URL_PARAM_NAME]);
        } else {
            self::$model->setApiUrl($config->get(self::API_URL_PARAM_NAME));
        }

        if (isset($request[self::SANDBOX_URL_PARAM_NAME])) {
            self::$model->setSandboxUrl($request[self::SANDBOX_URL_PARAM_NAME]);
        } else {
            self::$model->setSandboxUrl($config->get(self::SANDBOX_URL_PARAM_NAME));
        }

        if (isset($request[self::SECRET_WORD_NOTIFICATION_PARAM_NAME])) {
            self::$model->setSecretWordNotification($request[self::SECRET_WORD_NOTIFICATION_PARAM_NAME]);
        } else {
            self::$model->setSecretWordNotification($config->get(self::SECRET_WORD_NOTIFICATION_PARAM_NAME));
        }

        if (isset($request[self::INFO_PARAM_NAME])) {
            self::$model->setInfo($request[self::INFO_PARAM_NAME]);
        } else {
            self::$model->setInfo($config->get(self::INFO_PARAM_NAME));
        }

        if (isset($request[self::IS_NAME_EDIT_PARAM_NAME])) {
            self::$model->setIsNameEdit($request[self::IS_NAME_EDIT_PARAM_NAME]);
        } else {
            self::$model->setIsNameEdit($config->get(self::IS_NAME_EDIT_PARAM_NAME));
        }

        if (isset($request[self::IS_AMOUNT_EDIT_PARAM_NAME])) {
            self::$model->setIsAmountEdit($request[self::IS_AMOUNT_EDIT_PARAM_NAME]);
        } else {
            self::$model->setIsAmountEdit($config->get(self::IS_AMOUNT_EDIT_PARAM_NAME));
        }

        if (isset($request[self::IS_ADDRESS_EDIT_PARAM_NAME])) {
            self::$model->setIsAddressEdit($request[self::IS_ADDRESS_EDIT_PARAM_NAME]);
        } else {
            self::$model->setIsAddressEdit($config->get(self::IS_ADDRESS_EDIT_PARAM_NAME));
        }

        if (isset($request[self::STATUS_PARAM_NAME])) {
            self::$model->setStatus($request[self::STATUS_PARAM_NAME]);
        } else {
            self::$model->setStatus($config->get(self::STATUS_PARAM_NAME));
        }

        if (isset($request[self::PROCESSED_STATUS_ID_PARAM_NAME])) {
            self::$model->setProcessedStatus($request[self::PROCESSED_STATUS_ID_PARAM_NAME]);
        } else {
            self::$model->setProcessedStatus($config->get(self::PROCESSED_STATUS_ID_PARAM_NAME));
        }

        if (isset($request[self::SORT_ORDER_PARAM_NAME])) {
            self::$model->setSortOrder($request[self::SORT_ORDER_PARAM_NAME]);
        } else {
            self::$model->setSortOrder($config->get(self::SORT_ORDER_PARAM_NAME));
        }

        if (isset($request[self::MESSAGE_SUCCESS_PARAM_NAME])) {
            self::$model->setMessageSuccess($request[self::MESSAGE_SUCCESS_PARAM_NAME]);
        } else {
            self::$model->setMessageSuccess($config->get(self::MESSAGE_SUCCESS_PARAM_NAME));
        }

        if (isset($request[self::FAIL_STATUS_ID_PARAM_NAME])) {
            self::$model->setFailStatus($request[self::FAIL_STATUS_ID_PARAM_NAME]);
        } else {
            self::$model->setFailStatus($config->get(self::FAIL_STATUS_ID_PARAM_NAME));
        }

        if (isset($request[self::SUCCESS_STATUS_ID_PARAM_NAME])) {
            self::$model->setSuccessStatus($request[self::SUCCESS_STATUS_ID_PARAM_NAME]);
        } else {
            self::$model->setSuccessStatus($config->get(self::SUCCESS_STATUS_ID_PARAM_NAME));
        }


        return $this->exportParametersInArray($data);
    }

    private function exportParametersInArray($data)
    {
        $data[self::TOKEN_PARAM_NAME]                           = self::$model->getToken();
        $data[self::SERVICE_ID_PARAM_NAME]                      = self::$model->getServiceId();
        $data[self::SERVICE_PROVIDER_ID_PARAM_NAME]             = self::$model->getServiceProviderId();
        $data[self::EPOS_SERVICE_ID_PARAM_NAME]                 = self::$model->getEposServiceId();
        $data[self::NOTIFICATION_URL_PARAM_NAME]                = self::$model->getNotificationUrl();
        $data[self::SECRET_WORD_PARAM_NAME]                     = self::$model->getSecretWord();
        $data[self::USE_SIGNATURE_FOR_NOTIFICATION_PARAM_NAME]  = self::$model->getUseSignatureNotification();
        $data[self::IS_TEST_MODE_PARAM_NAME]                    = self::$model->getIsTestMode();
        $data[self::API_URL_PARAM_NAME]                         = self::$model->getApiUrl();
        $data[self::SANDBOX_URL_PARAM_NAME]                     = self::$model->getSandboxUrl();
        $data[self::SECRET_WORD_NOTIFICATION_PARAM_NAME]        = self::$model->getSecretWordNotification();
        $data[self::INFO_PARAM_NAME]                            = self::$model->getInfo();
        $data[self::IS_NAME_EDIT_PARAM_NAME]                    = self::$model->getIsNameEdit();
        $data[self::IS_AMOUNT_EDIT_PARAM_NAME]                  = self::$model->getIsAmountEdit();
        $data[self::IS_ADDRESS_EDIT_PARAM_NAME]                 = self::$model->getIsAddressEdit();
        $data[self::STATUS_PARAM_NAME]                          = self::$model->getStatus();
        $data[self::PROCESSED_STATUS_ID_PARAM_NAME]             = self::$model->getProcessedStatus();
        $data[self::SORT_ORDER_PARAM_NAME]                      = self::$model->getSortOrder();
        $data[self::MESSAGE_SUCCESS_PARAM_NAME]                 = self::$model->getMessageSuccess();
        $data[self::FAIL_STATUS_ID_PARAM_NAME]                  = self::$model->getFailStatus();
        $data[self::SUCCESS_STATUS_ID_PARAM_NAME]               = self::$model->getSuccessStatus();


        return $data;
    }
}

class EposExpressPayModel
{
    private $token;
    private $serviceId;
    private $serviceProviderId;
    private $eposServiceId;
    private $notificationUrl;
    private $secretWord;
    private $secretWordNotification;
    private $useSignatureNotification;
    private $isTestMode;
    private $apiUrl;
    private $sandboxUrl;
    private $info;
    private $isNameEdit;
    private $isAmountEdit;
    private $isAddressEdit;
    private $status;
    private $sortOrder;
    private $messageSuccess;
    private $processedStatus;
    private $failStatus;
    private $successStatus;


    public function __construct()
    {
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function setServiceId($serviceId)
    {
        $this->serviceId = $serviceId;
    }

    public function getServiceProviderId()
    {
        return $this->serviceProviderId;
    }

    public function setServiceProviderId($serviceProviderId)
    {
        $this->serviceProviderId = $serviceProviderId;
    }

    public function getEposServiceId()
    {
        return $this->eposServiceId;
    }

    public function setEposServiceId($eposServiceId)
    {
        $this->eposServiceId = $eposServiceId;
    }

    public function getNotificationUrl()
    {
        return HTTPS_CATALOG . 'index.php?route=extension/payment/epos_expresspay/notify';
    }

    public function setNotificationUrl($notificationUrl)
    {
        $this->notificationUrl = $notificationUrl;
    }

    public function getSecretWord()
    {
        return $this->secretWord;
    }

    public function setSecretWord($secretWord)
    {
        $this->secretWord = $secretWord;
    }

    public function getSecretWordNotification()
    {
        return $this->secretWordNotification;
    }

    public function setSecretWordNotification($secretWordNotification)
    {
        $this->secretWordNotification = $secretWordNotification;
    }

    public function getUseSignatureNotification()
    {
        return $this->useSignatureNotification;
    }

    public function setUseSignatureNotification($useSignatureNotification)
    {
        $checkboxValue = $this->normCheckboxValue($useSignatureNotification);
        $this->useSignatureNotification = $checkboxValue;
    }

    public function getIsTestMode()
    {
        return $this->isTestMode;
    }

    public function setIsTestMode($isTestMode)
    {
        $checkboxValue = $this->normCheckboxValue($isTestMode);
        $this->isTestMode = $checkboxValue;
    }

    public function getApiUrl()
    {
        return 'https://api.express-pay.by';
    }

    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getSandboxUrl()
    {
        return 'https://sandbox-api.express-pay.by';
    }

    public function setSandboxUrl($sandboxUrl)
    {
        $this->sandboxUrl = $sandboxUrl;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function setInfo($info)
    {
        $this->info = $info;
    }

    public function getIsNameEdit()
    {
        return $this->isNameEdit;
    }

    public function setIsNameEdit($isNameEdit)
    {
        $checkboxValue = $this->normCheckboxValue($isNameEdit);
        $this->isNameEdit = $checkboxValue;
    }

    public function getIsAmountEdit()
    {
        return $this->isAmountEdit;
    }

    public function setIsAmountEdit($isAmountEdit)
    {
        $checkboxValue = $this->normCheckboxValue($isAmountEdit);
        $this->isAmountEdit = $checkboxValue;
    }

    public function getIsAddressEdit()
    {
        return $this->isAddressEdit;
    }

    public function setIsAddressEdit($isAddressEdit)
    {
        $checkboxValue = $this->normCheckboxValue($isAddressEdit);
        $this->isAddressEdit = $checkboxValue;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getProcessedStatus()
    {
        return $this->processedStatus;
    }

    public function setProcessedStatus($processedStatus)
    {
        $this->processedStatus = $processedStatus;
    }

    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    }

    public function getMessageSuccess()
    {
        return $this->messageSuccess;
    }

    public function setMessageSuccess($messageSuccess)
    {
        $this->messageSuccess = $messageSuccess;
    }

    public function getFailStatus()
    {
        return $this->failStatus;
    }

    public function setFailStatus($failStatus)
    {
        $this->failStatus = $failStatus;
    }

    public function getSuccessStatus()
    {
        return $this->successStatus;
    }

    public function setSuccessStatus($successStatus)
    {
        $this->successStatus = $successStatus;
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

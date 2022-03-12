<?php
// Heading
$_['heading_title']         = 'Express pay: E-POS';

// Text
$_['text_extension']        = 'Extensions';
$_['text_success']          = 'You have successfully changed the module settings';
$_['text_epos_expresspay']  = '<a target="_blank" href="https://express-pay.by/extensions/opencart-3-x/epos">
                            <img src="view/image/payment/epos_expresspay.png" alt="Express-pay Website" title="Express-pay Website"/></a>';
$_['text_edit']             = 'Change Settings';

// Setting field
$_['namePaymentMethodLabel']            = 'Payment method name';
$_['namePaymentMethodTooltip']          = 'Name displayed when selecting high pay';
$_['namePaymentMethodDefault']          = 'Express pay: E-POS';
$_['tokenLabel']                        = 'Token';
$_['tokenTooltip']                      = 'API key of the service provider';
$_['serviceIdLabel']                    = 'Service number';
$_['serviceIdTooltip']                  = 'Service number in the system express-pay.by';
$_['secretWordLabel']                   = 'Secret word for invoices';
$_['secretWordTooltip']                 = 'Secret word to form a digital signature for invoices';
$_['secretWordNotificationLabel']       = 'Secret word for notifications';
$_['secretWordNotificationTooltip']     = 'Secret word to form a digital signature for notifications';
$_['useSignatureForNotificationLabel']  = 'Use a digital signature for notifications';
$_['useTestModeLabel']                  = 'Use test mode';
$_['urlApiLabel']                       = 'API Address';
$_['urlApiTooltip']                     = 'Address to work with the API';
$_['urlSandboxLabel']                   = 'Sandbox API Address';
$_['urlSandboxTooltip']                 = 'Address to work with the sandbox API';
$_['infoLabel']                         = 'Order description';
$_['infoTooltip']                       = 'The order description will be displayed when paying to the client';
$_['infoDefault']                       = 'Order number ##order_id## in the store '. $_SERVER['HTTP_HOST'];
$_['urlForNotificationLabel']           = 'Address for receiving notifications';
$_['urlForNotificationTooltip']         = 'The address for receiving notifications about the status of the order to the site is set in the personal account';
$_['messageSuccessLabel']               = 'Message on successful invoice creation';
$_['messageSuccessTooltip']             = 'Message text on successful invoice creation for the client';
$_['messageSuccessDefault']             = 'Your order number: ##order_id##';
$_['entryStatus']                       = 'Status';
$_['entrySortOrder']                    = 'Sort order';

// Setting ERIP and EPOS
$_['showQrCodeLabel']           = 'Show QR code for payment';
$_['isNameEditableLabel']       = 'Allowed to change name';
$_['isAmountEditableLabel']     = 'Allowed to change the amount';
$_['isAddressEditableLabel']    = 'Allowed to change address';

// Setting EPOS
$_['serviceProviderIdLabel']    = 'Service provider code';
$_['serviceProviderIdTooltip']  = 'Service producer code in the system express-pay.by';
$_['eposServiceIdLabel']        = 'E-POS service code';
$_['eposServiceIdTooltip']      = 'E-POS service code in the system express-pay.by';

$_['processedOrderStatusLabel']     = 'New order status';
$_['processedOrderStatusTooltip']   = 'Set the status of the order received for processing';
$_['failOrderStatusLabel']          = 'Order status on error';
$_['failOrderStatusTooltip']        = 'Order status to be set when an error occurs';
$_['successOrderStatusLabel']       = 'Paid order status';
$_['successOrderStatusTooltip']     = 'Set the status of the order for which payment has been received';

// Error
$_['errorPermission']           = 'Warning: You have no rights to change the payment module settings!';
$_['errorNamePaymentMethod']    = 'The name of the payment method is mandatory';
$_['errorToken']                = 'The token is mandatory';
$_['errorServiceId']            = 'The service number is mandatory';
$_['errorServiceProviderId']    = 'The service producer code is mandatory';
$_['errorEposServiceId']        = 'E-POS service code is mandatory';
$_['errorAPIUrl']               = 'The API address is mandatory';
$_['errorSandboxUrl']           = 'The address of the test API is mandatory';
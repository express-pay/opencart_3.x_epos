<?php
// Heading
$_['heading_title']          = 'Экспресс платежи: E-POS';
$_['text_edit']              = 'Изменить настройки';

// Text
$_['text_extension']        = 'Расширения';
$_['text_success']        = 'Вы успешно изменили настройки модуля';

// Entry
$_['entry_order_status']                = 'Статус заказа после оплаты';
$_['entry_order_status_completed_text'] = 'Статус заказа (успешная оплата)';
$_['entry_order_status_failed_text']    = 'Статус заказа (неуспешная оплата)';
$_['entry_order_status_pending']        = 'Order Status Pending';
$_['entry_order_status_expiredate']     = 'Order Status Expiredate';
$_['entry_order_status_canceled']       = 'Order Status Canceled';
$_['entry_order_status_failed']         = 'Order Status Failed';
$_['entry_order_status_processing']     = 'Order Status Processing';

$_['entry_status']           = 'Статус';
$_['entry_sort_order']       = 'Порядок сортировки';
$_['entrySortOrder']       = 'Порядок сортировки';

$_['tokenLabel'] = 'Токен';
$_['tokenTooltip'] = 'API-ключ производителя услуг';

$_['secretWordLabel'] = 'Секретное слово';
$_['secretWordTooltip'] = 'Секретное слово для формирования цифровой подписи';

$_['secretWordNotificationLabel'] = 'Секретное слово для уведомлений';
$_['secretWordNotificationTooltip'] = 'Секретное слово для формирования цифровой подписи для уведомлений';

$_['useSignatureForNotificationLabel'] = 'Использовать цифровую подпись для уведомлений';

$_['showQrCodeLabel'] = 'Показывать QR код для оплаты';

$_['useTestModeLabel'] = 'Использовать тестовый режим';

$_['serviceProviderIdLabel'] = 'Код производителя услуг';
$_['serviceProviderIdTooltip'] = 'Код производителя услуг в системе express-pay.by';

$_['serviceIdLabel'] = 'Номер услуги';
$_['serviceIdTooltip'] = 'Номер услуги в системе express-pay.by';

$_['eposServiceIdLabel'] = 'Код услуги E-POS';
$_['eposServiceIdTooltip'] = 'Код услуги E-POS в системе express-pay.by';

$_['infoLabel'] = 'Описание заказа';
$_['infoTooltip'] = 'Описание заказа будет отображаться при оплате клиенту';
$_['infoDefault'] = 'Заказ номер ##orderId## в магазине ' . HTTPS_SERVER;

$_['isNameEditableLabel'] = 'Разрешено изменять ФИО';
$_['isAmountEditableLabel'] = 'Разрешено изменять сумму';
$_['isAddressEditableLabel'] = 'Разрешено изменять адрес';

$_['messageSuccessLabel'] = 'Сообщение при успешном заказе';

$_['urlForNotificationLabel'] = 'Адрес для получения уведомлений';
$_['urlForNotificationTooltip'] = 'Адрес для получения уведомлений о статусе заказа на сайт, задается в личном кабинете.';

$_['urlApiLabel'] = 'Адрес API';
$_['urlApiTooltip'] = 'Адрес для работы с API.';

$_['urlSandboxLabel'] = 'Адрес тестового API';
$_['urlSandboxTooltip'] = 'Адрес для работы с тестовым API.';

$_['entry_companyid']      = 'Id магазина';
$_['entry_companyid_help'] = 'Вы можете найти Id магазина в вашем личном кабинете на странице настроек магазина';
$_['entry_encyptionkey']   = 'Ключ магазина';
$_['entry_encyptionkey_help']  = 'Вы можете найти ключ магазина в вашем личном кабинете на странице настроек магазина';
$_['entry_domain_payment_page']      = 'Домен страницы оплаты';
$_['entry_domain_payment_page_help'] = 'Домен страницы оплаты, полученный от вашей платежной компании';
$_['entry_payment_type']        = 'Способ оплаты';

// Error
$_['errorPermission']      = 'Внимание: У вас нет прав для изменения настроек модуля оплаты!';
$_['error_companyid']       = 'Id магазина обязателен!';
$_['error_encyptionkey']    = 'Ключ магазина обязателен!';
$_['error_domain_payment_page']    = 'Домен платежного шлюза обязателен!';
$_['error_payment_type']        = 'Требуется указать доступные способы оплаты!';
$_['error_epos_service_no'] = 'Код услуги ЕРИП обязателен!';

$_['eposExpressPayTokenError'] = 'Токен является обязательным';
$_['eposExpressPayServiceIdError'] = 'Номер услуги является обязательным';

$_['processed_order_status'] = 'Статус нового платежа поступившего в обработку';
$_['fail_order_status'] = 'Статус платежа при ошибке ошибке выставления';
$_['success_order_status'] = 'Статус платежа на который поступила оплата';

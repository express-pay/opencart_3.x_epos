<?php
// Heading
$_['heading_title']         = 'Express payments: E-POS';
$_['heading_title_success'] = 'An invoice was added for payment via the E-POS system';
$_['heading_title_fail']    = 'Billing error for payment via E-POS system';

// Content
$_['text_message_success']  = 'Your order number: ##order_id##';
$_['content_success'] = 'Payment must be made in any system that allows you to pay via ERIP '.
'(banking services goods, ATMs, payment terminals, Internet banking systems, client-banking, etc.).'.
'<br> 1. To do this, in the list of ERIP services, go to the section: ##erip_path##'.
'<br> 2. In the Code field enter <b>##order_id##</b> and press "Continue"'.
'<br> 3. Check that the information is correct'.
'<br> 4. Make a payment';

$_['text_message_fail']  = 'An unexpected error occurred while executing your request. Please repeat your request later or contact the technical support team of the store.';

$_['erip_path']         = 'Settlement system (ERIP)->E-POS service->E-POS->Payments for goods and services';
$_['qr_description']    = 'Scan the QR code to pay';

$_['text_basket']       = 'Basket';
$_['text_checkout']     = 'Ordering';
$_['text_loading']      = 'Loading...';
$_['button_continue']   = 'Continue';
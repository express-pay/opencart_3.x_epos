<?php
class ControllerExtensionPaymentEposExpressPay extends Controller
{
    public function index()
    {

        $this->load->model('extension/payment/epos_expresspay');

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['text_loading'] = $this->language->get('text_loading');

        $this->log_info("index", "Try to get order_id");

        $data['AccountNo'] = $this->session->data['order_id'];

        $this->log_info("index", "ORDER_ID: " . $data['AccountNo']);

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $amount = str_replace('.', ',', $this->currency->format($order_info['total'], $this->session->data['currency'], '', false));

        $data['Amount'] = $amount;

        $data['Surname'] = $order_info['lastname'];

        $data['FirstName'] = $order_info['firstname'];

        $data['EmailNotification'] = $order_info['email'];



        //Обрезать +
        //заменить знаки (' ', '-','(',')') на пустую строку
        //посчитать количество символов
        //Если номер не прошел проверку, не использовать
        $data['SmsPhone'] = $order_info['telephone'];

        $data['SmsPhone'] = str_replace('+', '', $data['SmsPhone']);
        $data['SmsPhone'] = str_replace(' ', '', $data['SmsPhone']);
        $data['SmsPhone'] = str_replace('-', '', $data['SmsPhone']);
        $data['SmsPhone'] = str_replace('(', '', $data['SmsPhone']);
        $data['SmsPhone'] = str_replace(')', '', $data['SmsPhone']);

        $data = $this->model_extension_payment_epos_expresspay->setParams($data, $this->config);

        $this->log_info("index", "DATA: " . json_encode($data));

        return $this->load->view('extension/payment/epos_expresspay', $data);
    }

    public function confirm()
    {
        return true;
    }

    //Test Link = http://opencart3:8080/index.php?route=extension/payment/epos_expresspay/success?ExpressPayAccountNumber=21&ExpressPayInvoiceNo=347153&Signature=9047222EE0A0C53A8F3C8883885575FD1734AFF7

    public function success()
    {
        $this->load->model('extension/payment/epos_expresspay');

        /*if(!$this->model_extension_payment_epos_expresspay->checkResponse($_REQUEST['Signature'], $_REQUEST, $this->config))
        {
            echo 'die';
            die();
        }
        */


        $this->cart->clear();

        $this->load->language('extension/payment/epos_expresspay');
        $data['heading_title'] = $this->language->get('heading_title');
        $this->document->setTitle($this->data['heading_title']);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/home'),
            'text'      => $this->language->get('text_home'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('checkout/cart'),
            'text'      => $this->language->get('text_basket'),
            'separator' => $this->language->get('text_separator')
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
            'text'      => $this->language->get('text_checkout'),
            'separator' => $this->language->get('text_separator')
        );

        $data['button_continue'] = $this->language->get('button_continue');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['continue'] = $this->url->link('common/home');
        $data['test_mode_label'] = $this->language->get('test_mode_label');
        $data['text_send_notify_success'] = $this->language->get('text_send_notify_success');
        $data['text_send_notify_cancel'] = $this->language->get('text_send_notify_cancel');
        $data['message_success'] = nl2br($this->config->get('payment_epos_expresspay_message_success'), true);
        $data['order_id'] = $this->config->get('payment_epos_expresspay_service_provider_id');
        $data['order_id'] .= '-' . $this->config->get('payment_epos_expresspay_epos_service_id');
        $data['order_id'] .= '-' . $this->session->data['order_id'];
        $data['message_success'] = str_replace("##order_id##", $data['order_id'], $data['message_success']);
        $data['is_use_signature'] = ($this->config->get('epos_expresspay_sign_invoices') == 'on') ? true : false;
        $data['signature_success'] = $data['signature_cancel'] = "";

        $request_params = array(
            "Token" => $this->config->get('payment_epos_expresspay_token'),
            "InvoiceId" => $_REQUEST['ExpressPayInvoiceNo'],
            'ViewType' => 'base64'
        );

        $request_params["Signature"] = $this->model_extension_payment_epos_expresspay->getSignatureForQr($request_params, $this->config);

        $request_params = http_build_query($request_params);

        $url = $this->model_extension_payment_epos_expresspay->getQrCodeLink($this->config) . '?' . $request_params;

        $this->log_info('Success', 'URL: ' . $url);
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $e) {
            $this->log_error_exception('success', 'Get response; INVOICE ID - ' . $invoiceId . '; RESPONSE - ' . $response, $e);
            return;
        }

        $this->log_info('success', $response);
        try {
            $response = json_decode($response);
        } catch (Exception $e) {
            $this->log_error_exception('receipt_page', 'Get response; ORDER ID - ' . $order_id . '; RESPONSE - ' . $response, $e);
        }

        if (isset($response->QrCodeBody)) {
            $qr_code = $response->QrCodeBody;
            $data['qr_code'] = '<img src="data:image/jpeg;base64,' . $qr_code . '"  width="150" height="150"/>';
            $data['qr_description'] = '<div style="padding:0 15px"><b>Отсканируйте QR-код для оплаты</b></div>';
        }


        $this->load->model('checkout/order');
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_epos_expresspay_processed_status_id'));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/payment/epos_expresspay_successful', $data));
    }

    public function fail()
    {
        $this->load->language('extension/payment/epos_expresspay');
        $data['heading_title'] = $this->language->get('heading_title_error');
        $data['text_message'] = $this->language->get('text_message_error');
        $this->document->setTitle($this->data['heading_title']);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('common/home'),
            'text'      => $this->language->get('text_home'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('checkout/cart'),
            'text'      => $this->language->get('text_basket'),
            'separator' => $this->language->get('text_separator')
        );

        $data['breadcrumbs'][] = array(
            'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
            'text'      => $this->language->get('text_checkout'),
            'separator' => $this->language->get('text_separator')
        );

        $data['message_fail'] = nl2br($this->config->get('epos_expresspay_message_fail'), true);
        $data['order_id'] = $this->session->data['order_id'];
        $data['message_fail'] = str_replace("##order_id##", $data['order_id'], $data['message_fail']);
        $data['button_continue'] = $this->language->get('button_continue');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['continue'] = $this->url->link('checkout/checkout');

        $this->load->model('checkout/order');
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_epos_expresspay_fail_status_id'));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');


        $this->response->setOutput($this->load->view('extension/payment/epos_expresspay_failure', $data));
    }

    public function notify()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $secret_word = $this->config->get('payment_epos_expresspay_secret_word_for_notification');
            $is_use_signature = ($this->config->get('payment_epos_expresspay_is_use_signature_for_notification') == 'on') ? true : false;
            $data = (isset($this->request->post['Data'])) ? htmlspecialchars_decode($this->request->post['Data']) : '';
            $signature = (isset($this->request->post['Signature'])) ? $this->request->post['Signature'] : '';

            if ($is_use_signature) {
                if ($signature == $this->compute_signature($data, $secret_word))
                    $this->notify_success($data);
                else
                    $this->notify_fail($data);
            } else
                $this->notify_success($data);
        }
    }

    private function notify_success($dataJSON)
    {
        try {
            $data = json_decode($dataJSON);
        } catch (Exception $e) {
            $this->notify_fail($dataJSON);
        }

        if (isset($data->CmdType)) {
            switch ($data->CmdType) {
                case '1':
                    $this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get('payment_epos_expresspay_success_status_id'));
                    break;
                case '2':
                    $this->model_checkout_order->addOrderHistory($data->AccountNo, $this->config->get('payment_epos_expresspay_fail_status_id'));
                    break;
                default:
                    $this->notify_fail($dataJSON);
                    die();
            }

            header("HTTP/1.0 200 OK");
            echo 'SUCCESS';
        } else
            $this->notify_fail($dataJSON);
    }

    private function notify_fail($dataJSON)
    {
        header("HTTP/1.0 400 Bad Request");
        echo 'FAILED | Incorrect digital signature';
    }

    function compute_signature($json, $secretWord)
    {
        $hash = NULL;

        $secretWord = trim($secretWord);

        if (empty($secretWord))
            $hash = strtoupper(hash_hmac('sha1', $json, ""));
        else
            $hash = strtoupper(hash_hmac('sha1', $json, $secretWord));
        return $hash;
    }

    private function log_error_exception($name, $message, $e)
    {
        $this->log($name, "ERROR", $message . '; EXCEPTION MESSAGE - ' . $e->getMessage() . '; EXCEPTION TRACE - ' . $e->getTraceAsString());
    }

    private function log_error($name, $message)
    {
        $this->log($name, "ERROR", $message);
    }

    private function log_info($name, $message)
    {
        $this->log($name, "INFO", $message);
    }

    private function log($name, $type, $message)
    {
        $log_url = 'system/storage/logs/epos_expresspay';

        if (!file_exists($log_url)) {
            $is_created = mkdir($log_url, 0777);

            if (!$is_created)
                return;
        }

        $log_url .= '/express-pay-' . date('Y.m.d') . '.log';

        file_put_contents($log_url, $type . " - IP - " . $_SERVER['REMOTE_ADDR'] . "; DATETIME - " . date("Y-m-d H:i:s") . "; USER AGENT - " . $_SERVER['HTTP_USER_AGENT'] . "; FUNCTION - " . $name . "; MESSAGE - " . $message . ';' . PHP_EOL, FILE_APPEND);
    }
}

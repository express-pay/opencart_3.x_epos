<?php

/**
 * @package       ExpressPay Payment Module for OpenCart
 * @author        ООО "ТриИнком" <info@express-pay.by>
 * @copyright     (c) 2022 Экспресс Платежи. Все права защищены.
 */

class ControllerExtensionPaymentEposExpressPay extends Controller{
    const IS_SHOW_QR_CODE_PARAM_NAME                = 'payment_epos_expresspay_is_show_qr_code';
    const SERVICE_PROVIDER_ID_PARAM_NAME            = 'payment_epos_expresspay_service_provider_id';
    const EPOS_SERVICE_ID_PARAM_NAME                = 'payment_epos_expresspay_epos_service_id';
    const PATH_IN_ERIP_PARAM_NAME                   = 'payment_epos_expresspay_path_in_erip';
    const MESSAGE_SUCCESS_PARAM_NAME                = 'payment_epos_expresspay_message_success';
    const PROCESSED_STATUS_ID_PARAM_NAME            = 'payment_epos_expresspay_processed_status_id';
    const FAIL_STATUS_ID_PARAM_NAME                 = 'payment_epos_expresspay_fail_status_id';

    public function index()
    {
        $this->load->model('extension/payment/epos_expresspay');
        $this->load->model('extension/payment/epos_expresspay_log');

        $data['button_confirm'] = $this->language->get('button_confirm');
        $data['text_loading'] = $this->language->get('text_loading');

        $data = $this->model_extension_payment_epos_expresspay->setParams($data, $this->config);

        $this->model_extension_payment_epos_expresspay_log->log_info("index", "DATA: " . json_encode($data));

        return $this->load->view('extension/payment/epos_expresspay', $data);
    }

    public function confirm()
    {
        return true;
    }

    public function success()
    {
        $this->cart->clear();
        $this->load->model('extension/payment/epos_expresspay');
        $this->load->model('extension/payment/epos_expresspay_log');
        $this->load->language('extension/payment/epos_expresspay');
        $orderId = $this->session->data['order_id'];
        $this->model_extension_payment_epos_expresspay_log->log_info("successStart", "Order Id: " . $orderId);
        $headingTitle = $this->language->get('heading_title_success');
        $this->document->setTitle($headingTitle);
        $data['heading_title'] = $headingTitle;

        $textMessage = $this->config->get(self::MESSAGE_SUCCESS_PARAM_NAME);
        if (empty($textMessage)) {
            $textMessage = $this->language->get('text_message_success');
        }
        $data['text_message'] = nl2br(str_replace('##order_id##', $orderId, $textMessage));

        $eripPath = $this->config->get(self::PATH_IN_ERIP_PARAM_NAME);
        if (empty($eripPath)) {
            $eripPath = $this->language->get('erip_path');
        }
        $data['content_body'] = str_replace('##erip_path##', $eripPath, $this->language->get('content_success'));
        
        $eposCode = $this->config->get(self::SERVICE_PROVIDER_ID_PARAM_NAME).'-'.$this->config->get(self::EPOS_SERVICE_ID_PARAM_NAME).'-'.$orderId;
        $data['content_body'] = nl2br(str_replace('##order_id##', $eposCode, $data['content_body']));
        

        $data['button_continue'] = $this->language->get('button_continue');
        $data['text_loading'] = $this->language->get('text_loading');
        
        if ($this->config->get(self::IS_SHOW_QR_CODE_PARAM_NAME)  == 'on' && isset($this->request->get['ExpressPayInvoiceNo'])) {
            $invoiceNo = $this->request->get['ExpressPayInvoiceNo'];
            try {
                $qrbase64json = $this->model_extension_payment_epos_expresspay->getQrbase64($invoiceNo, $this->config);
                $qrbase64 = json_decode($qrbase64json);
                if (isset($qrbase64->QrCodeBody))
                {
                    $data['qr_code'] = $qrbase64->QrCodeBody;
                    $data['show_qr_code'] = 1;
                }
            } catch (Exception $e) {
                $this->model_extension_payment_epos_expresspay_log->log_error_exception('success', 'Get response; INVOICE ID - ' . $invoiceNo. '; RESPONSE - ' . $qrbase64json, $e);
            }
        }

        $this->load->model('checkout/order');
        $this->model_checkout_order->addOrderHistory($orderId, $this->config->get(self::PROCESSED_STATUS_ID_PARAM_NAME));

        $data = $this->setBreadcrumbs($data);
        $data = $this->setButtons($data);
        $data = $this->setController($data);
        $data['continue'] = $this->url->link('common/home');

        $this->model_extension_payment_epos_expresspay_log->log_info("successFinish", "DATA: " . json_encode($data));
        $this->response->setOutput($this->load->view('extension/payment/epos_expresspay_successful', $data));
    }

    public function fail()
    {
        $this->load->model('extension/payment/epos_expresspay_log');
        $this->load->language('extension/payment/epos_expresspay');
        $orderId = $this->session->data['order_id'];
        $this->model_extension_payment_epos_expresspay_log->log_info("failStart", "Order Id: " . $orderId);
        $headingTitle = $this->language->get('heading_title_fail');
        $this->document->setTitle($headingTitle);
        $data['heading_title'] = $headingTitle;

        $data['text_message'] = nl2br(str_replace('##order_id##', $orderId, $this->language->get('text_message_fail')));

        $this->load->model('checkout/order');
        $this->model_checkout_order->addOrderHistory($orderId, $this->config->get(self::FAIL_STATUS_ID_PARAM_NAME));

        $data = $this->setBreadcrumbs($data);
        $data = $this->setButtons($data);
        $data = $this->setController($data);
        $data['continue'] = $this->url->link('checkout/checkout');

        $this->model_extension_payment_epos_expresspay_log->log_info("failFinish", "DATA: " . json_encode($data));
        $this->response->setOutput($this->load->view('extension/payment/epos_expresspay_failure', $data));
    }

    private function setButtons($data)
    {
        $data['button_continue'] = $this->language->get('button_continue');
        $data['text_loading'] = $this->language->get('text_loading');
        $data['continue'] = $this->url->link('checkout/checkout');

        return $data;
    }

    private function setController($data)
    {
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        return $data;
    }

    private function setBreadcrumbs($data)
    {
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

        return $data;
    }
}

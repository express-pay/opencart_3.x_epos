<?php

/**
 * @package       ExpressPay Payment Module for OpenCart
 * @author        ООО "ТриИнком" <info@express-pay.by>
 * @copyright     (c) 2022 Экспресс Платежи. Все права защищены.
 */

class ControllerExtensionPaymentEposExpresspay extends Controller
{
  const VERSION_EXTENSION                         = '1.0.2';
  const NAME_PAYMENT_METHOD                       = 'payment_epos_expresspay_name_payment_method';
  const TOKEN_PARAM_NAME                          = 'payment_epos_expresspay_token';
  const SERVICE_ID_PARAM_NAME                     = 'payment_epos_expresspay_service_id';
  const SERVICE_PROVIDER_ID_PARAM_NAME            = 'payment_epos_expresspay_service_provider_id';
  const EPOS_SERVICE_ID_PARAM_NAME                = 'payment_epos_expresspay_epos_service_id';
  const API_URL_PARAM_NAME                        = 'payment_epos_expresspay_api_url';
  const SANDBOX_URL_PARAM_NAME                    = 'payment_epos_expresspay_sandbox_url';

  private $error = array();

  public function index()
  {
    $this->loadResource();

    $this->document->setTitle($this->language->get('heading_title'));

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) 
    {
      $this->model_setting_setting->editSetting('payment_epos_expresspay', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
    }

    $data = array();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		if (isset($this->error[self::NAME_PAYMENT_METHOD])) {
			$data['error_name_payment_method'] = $this->error[self::NAME_PAYMENT_METHOD];
		} else {
			$data['error_name_payment_method'] = '';
		}
		if (isset($this->error[self::TOKEN_PARAM_NAME])) {
			$data['error_token'] = $this->error[self::TOKEN_PARAM_NAME];
		} else {
			$data['error_token'] = '';
		}
		if (isset($this->error[self::SERVICE_ID_PARAM_NAME])) {
			$data['error_service_id'] = $this->error[self::SERVICE_ID_PARAM_NAME];
		} else {
			$data['error_service_id'] = '';
		}
		if (isset($this->error[self::SERVICE_PROVIDER_ID_PARAM_NAME])) {
			$data['error_service_provider_id'] = $this->error[self::SERVICE_PROVIDER_ID_PARAM_NAME];
		} else {
			$data['error_service_provider_id'] = '';
		}
		if (isset($this->error[self::EPOS_SERVICE_ID_PARAM_NAME])) {
			$data['error_epos_service_id'] = $this->error[self::EPOS_SERVICE_ID_PARAM_NAME];
		} else {
			$data['error_epos_service_id'] = '';
		}
		if (isset($this->error[self::API_URL_PARAM_NAME])) {
			$data['error_api_url'] = $this->error[self::API_URL_PARAM_NAME];
		} else {
			$data['error_api_url'] = '';
		}
		if (isset($this->error[self::SANDBOX_URL_PARAM_NAME])) {
			$data['error_sandbox_url'] = $this->error[self::SANDBOX_URL_PARAM_NAME];
		} else {
			$data['error_sandbox_url'] = '';
		}

    $data = $this->model_extension_payment_epos_expresspay->setParametersFromConfig($this->config, $this->request->post, $data);

    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    $data['user_token'] = $this->session->data['user_token'];
    $data['version_extension'] = self::VERSION_EXTENSION;

    $data = $this->setBreadcrumbs($data);
    $data = $this->setButtons($data);
    $data = $this->setController($data);

    $this->response->setOutput($this->load->view('extension/payment/epos_expresspay', $data));
  }

  private function validate()
  {
    if (!$this->user->hasPermission('modify', 'extension/payment/epos_expresspay')) {
      $this->error['warning'] = $this->language->get('errorPermission');
    }

    // Empty Название метода оплаты
    if(!$this->request->post[self::NAME_PAYMENT_METHOD])
    {
      $this->error[self::NAME_PAYMENT_METHOD] = $this->language->get('errorNamePaymentMethod');
    }
    // Empty Token
    if(!$this->request->post[self::TOKEN_PARAM_NAME])
    {
      $this->error[self::TOKEN_PARAM_NAME] = $this->language->get('errorToken');
    }
    // Empty Номер услуги
    if(!$this->request->post[self::SERVICE_ID_PARAM_NAME])
    {
      $this->error[self::SERVICE_ID_PARAM_NAME] = $this->language->get('errorServiceId');
    }
    
    if(!$this->request->post[self::SERVICE_PROVIDER_ID_PARAM_NAME])
    {
      $this->error[self::SERVICE_PROVIDER_ID_PARAM_NAME] = $this->language->get('errorServiceProviderId');
    }
    
    if(!$this->request->post[self::EPOS_SERVICE_ID_PARAM_NAME])
    {
      $this->error[self::EPOS_SERVICE_ID_PARAM_NAME] = $this->language->get('errorEposServiceId');
    }
    // Empty Адрес API
    if(!$this->request->post[self::API_URL_PARAM_NAME])
    {
      $this->error[self::API_URL_PARAM_NAME] = $this->language->get('errorAPIUrl');
    }
    // Empty Адрес тестового API
    if(!$this->request->post[self::SANDBOX_URL_PARAM_NAME])
    {
      $this->error[self::SANDBOX_URL_PARAM_NAME] = $this->language->get('errorSandboxUrl');
    }
    return !$this->error;
  }

  private function setButtons($data)
  {

    $data['action'] = $this->url->link('extension/payment/epos_expresspay', 'user_token=' . $this->session->data['user_token'], true);

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=payment', true);

    return $data;
  }

  private function setController($data)
  {
    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    return $data;
  }

  private function setBreadcrumbs($data)
  {
    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      =>  $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/payment/epos_expresspay', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => ' :: '
    );

    return $data;
  }

  private function loadResource()
  {
    $this->load->model('extension/payment/epos_expresspay');
    $this->load->language('extension/payment/epos_expresspay');
    $this->load->model('setting/setting');
    $this->load->model('localisation/order_status');
  }
}
<?php

class ControllerExtensionPaymentGate2playApple extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('payment/gate2play_apple');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        $data['heading_title'] = $this->language->get('heading_title');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

            $this->model_setting_setting->editSetting('gate2play_apple',  $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment/gate2play_apple', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/gate2play_apple', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['permission'])) {
            $data['error_permission'] = $this->error['permission'];
        } else {
            $data['error_permission'] = '';
        }

        if (isset($this->error['heading_title'])) {
            $data['error_heading_title'] = $this->error['heading_title'];
        } else {
            $data['error_heading_title'] = '';
        }

        if (isset($this->error['channel'])) {
            $data['error_channel'] = $this->error['channel'];
        } else {
            $data['error_channel'] = '';
        }

        if (isset($this->error['access_token'])) {
            $data['error_access_token'] = $this->error['access_token'];
        } else {
            $data['error_access_token'] = '';
        }


        //-------------------------------------------------------

        $data['text_edit'] = $this->language->get('text_edit');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        $data['action'] = $this->url->link('extension/payment/gate2play_apple', 'token=' . $this->session->data['token'], 'SSL');
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

        $data['entry_heading_title'] = $this->language->get('entry_heading_title');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_testmode'] = $this->language->get('entry_testmode');

        $data['entry_testmode_off'] = $this->language->get('entry_testmode_off');
        $data['entry_testmode_on'] = $this->language->get('entry_testmode_on');

        $data['entry_trans_type'] = $this->language->get('entry_trans_type');
        $data['entry_all_trans_type'] = $this->get_gate2play_apple_trans_type();

        $data['entry_trans_mode'] = $this->language->get('entry_trans_mode');
        $data['entry_all_trans_mode'] = $this->get_gate2play_apple_trans_mode();

        $data['entry_base_currency'] = $this->language->get('entry_base_currency');
        $data['entry_all_currencies'] = $this->get_all_currencies();

        $data['entry_channel'] = $this->language->get('entry_channel');
        $data['entry_access_token'] = $this->language->get('entry_access_token');

        $data['entry_brands'] = $this->language->get('entry_brands');
        $data['entry_all_brands'] = $this->get_gate2play_apple_payment_methods();

        $data['entry_supported_networks'] = $this->language->get('entry_supported_networks');
        $data['entry_all_supported_networks'] = $this->get_gate2play_apple_supported_networks();

        $data['entry_payment_style'] = $this->language->get('Payment Style');
        $data['entry_all_payment_style'] = $this->get_gate2play_apple_payment_style();

        $data['entry_mailerrors'] = $this->language->get('entry_mailerrors');
        $data['entry_mailerrors_enable'] = $this->language->get('entry_mailerrors_enable');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $data['entry_order_status'] = $this->language->get('entry_order_status');
        $data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');

        $data['entry_connector'] = $this->language->get('entry_connector');
        $data['entry_all_connector'] = $this->get_gate2play_apple_connector();
        //-----------------------------------------------------------------------

        if (isset($this->request->post['gate2play_apple_status'])) {
            $data['gate2play_apple_status'] = $this->request->post['gate2play_apple_status'];
        } else {
            $data['gate2play_apple_status'] = $this->config->get('gate2play_apple_status');
        }

        if (isset($this->request->post['gate2play_apple_base_currency'])) {
            $data['gate2play_apple_base_currency'] = $this->request->post['gate2play_apple_base_currency'];
        } else {
            $data['gate2play_apple_base_currency'] = $this->config->get('gate2play_apple_base_currency');
        }

        if (isset($this->request->post['gate2play_apple_sort_order'])) {
            $data['gate2play_apple_sort_order'] = $this->request->post['gate2play_apple_sort_order'];
        } elseif($this->config->get('gate2play_apple_sort_order')) {
            $data['gate2play_apple_sort_order'] = $this->config->get('gate2play_apple_sort_order');
        }else{
          $data['gate2play_apple_sort_order'] = 4;
        }

        if (isset($this->request->post['gate2play_apple_testmode'])) {
            $data['gate2play_apple_testmode'] = $this->request->post['gate2play_apple_testmode'];
        } else {
            $data['gate2play_apple_testmode'] = $this->config->get('gate2play_apple_testmode');
        }

        if (isset($this->request->post['gate2play_apple_trans_type'])) {
            $data['gate2play_apple_trans_type'] = $this->request->post['gate2play_apple_trans_type'];
        } else {
            $data['gate2play_apple_trans_type'] = $this->config->get('gate2play_apple_trans_type');
        }

        if (isset($this->request->post['gate2play_apple_trans_mode'])) {
            $data['gate2play_apple_trans_mode'] = $this->request->post['gate2play_apple_trans_mode'];
        } else {
            $data['gate2play_apple_trans_mode'] = $this->config->get('gate2play_apple_trans_mode');
        }

        if (isset($this->request->post['gate2play_apple_heading_title'])) {
            $data['gate2play_apple_heading_title'] = $this->request->post['gate2play_apple_heading_title'];
        } else {
            $data['gate2play_apple_heading_title'] = $this->config->get('gate2play_apple_heading_title');
        }

        if (isset($this->request->post['gate2play_apple_channel'])) {
            $data['gate2play_apple_channel'] = $this->request->post['gate2play_apple_channel'];
        } else {
            $data['gate2play_apple_channel'] = $this->config->get('gate2play_apple_channel');
        }

        if (isset($this->request->post['gate2play_apple_password'])) {
            $data['gate2play_apple_password'] = $this->request->post['gate2play_apple_password'];
        } else {
            $data['gate2play_apple_password'] = $this->config->get('gate2play_apple_password');
        }

        if (isset($this->request->post['gate2play_apple_brands'])) {
            $data['gate2play_apple_brands'] = $this->request->post['gate2play_apple_brands'];
        } else {
            $data['gate2play_apple_brands'] = $this->config->get('gate2play_apple_brands');
        }

        if (isset($this->request->post['gate2play_apple_supported_networks'])) {
            $data['gate2play_apple_supported_networks'] = $this->request->post['gate2play_apple_supported_networks'];
        } else {
            $data['gate2play_apple_supported_networks'] = $this->config->get('gate2play_apple_supported_networks');
        }

        if (isset($this->request->post['gate2play_apple_payment_style'])) {
            $data['gate2play_apple_payment_style'] = $this->request->post['gate2play_apple_payment_style'];
        } else {
            $data['gate2play_apple_payment_style'] = $this->config->get('gate2play_apple_payment_style');
        }

        if (isset($this->request->post['gate2play_apple_mailerrors'])) {
            $data['gate2play_apple_mailerrors'] = $this->request->post['gate2play_apple_mailerrors'];
        } else {
            $data['gate2play_apple_mailerrors'] = $this->config->get('gate2play_apple_mailerrors');
        }

        if (isset($this->request->post['gate2play_apple_mailerrors_enable'])) {
            $data['gate2play_apple_mailerrors_enable'] = $this->request->post['gate2play_apple_mailerrors_enable'];
        } else {
            $data['gate2play_apple_mailerrors_enable'] = $this->config->get('gate2play_apple_mailerrors_enable');
        }

        $data['gate2play_apple_admin_email'] = $this->config->get('config_email');

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        if (isset($this->request->post['gate2play_apple_order_status_id'])) {
            $data['gate2play_apple_order_status_id'] = $this->request->post['gate2play_apple_order_status_id'];
        } else {
            $data['gate2play_apple_order_status_id'] = $this->config->get('gate2play_apple_order_status_id');
        }

        if (isset($this->request->post['gate2play_apple_order_status_failed_id'])) {
            $data['gate2play_apple_order_status_failed_id'] = $this->request->post['gate2play_apple_order_status_failed_id'];
        } else {
            $data['gate2play_apple_order_status_failed_id'] = $this->config->get('gate2play_apple_order_status_failed_id');
        }

        if (isset($this->request->post['gate2play_apple_access_token'])) {
            $data['gate2play_apple_access_token'] = $this->request->post['gate2play_apple_access_token'];
        } else {
            $data['gate2play_apple_access_token'] = $this->config->get('gate2play_apple_access_token');
        }

        if (isset($this->request->post['gate2play_apple_connector'])) {
            $data['gate2play_apple_connector'] = $this->request->post['gate2play_apple_connector'];
        } else {
            $data['gate2play_apple_connector'] = $this->config->get('gate2play_apple_connector');
        }


        $data['text_missing'] = $this->language->get('text_missing');


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/gate2play_apple.tpl', $data));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/payment/gate2play_apple')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['gate2play_apple_heading_title']) {
            $this->error['heading_title'] = $this->language->get('error_heading_title');
        }

        if (!$this->request->post['gate2play_apple_channel']) {
            $this->error['channel'] = $this->language->get('error_channel');
        }

        if (!$this->request->post['gate2play_apple_access_token']) {
            $this->error['access_token'] = $this->language->get('access_token');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    private function get_gate2play_apple_payment_methods()
    {
        $gate2play_apple_payments = array(
            'APPLEPAY' => 'Apple Pay',
        );

        return $gate2play_apple_payments;
    }

    function get_gate2play_apple_supported_networks()
    {
        $hyperpayApplePay_supported_networks = array(
            "amex" => "Amex",
            "masterCard" => "MasterCard",
            "visa" => "Visa",
            "mada" => "Mada"
        );

        return $hyperpayApplePay_supported_networks;
    }

    private function get_gate2play_apple_trans_mode()
    {
        $gate2play_apple_trans_mode = array(
            'CONNECTOR_TEST' => 'CONNECTOR_TEST',
            'INTEGRATOR_TEST' => 'INTEGRATOR_TEST',
            'LIVE' => 'LIVE'
        );

        return $gate2play_apple_trans_mode;
    }

    private function get_gate2play_apple_trans_type()
    {
        $gate2play_apple_trans_type = array(
            'DB' => 'Debit',
            'PA' => 'Pre-Authorization'
        );

        return $gate2play_apple_trans_type;
    }

    private function get_gate2play_apple_payment_style()
    {
        $gate2play_apple_payment_style = array(
            'card' => 'Card',
            'plain' => 'Plain'
        );

        return $gate2play_apple_payment_style;
    }

    private function get_all_currencies()
    {
        $this->load->model('localisation/currency');
        $currencyArray = [];
        $currencyArray = $this->model_localisation_currency->getCurrencies();
        $all = [];
        foreach ($currencyArray as $currency) {

            $all[$currency['code']] = $currency['code'];
        }
        return $all;
    }

    private function get_gate2play_apple_connector()
    {
        $gate2play_apple_connector = array(
            'visa' => 'VISA ACP',
            'migs' => 'MIGS / MPGS'
        );
        return $gate2play_apple_connector;
    }
}

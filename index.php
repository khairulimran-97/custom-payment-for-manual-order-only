<?php 

add_action('woocommerce_payment_gateways', 'add_manual_payment_gateways');

function add_manual_payment_gateways($gateways) {
    // Check if manual orders are being placed
    if (is_admin() && !defined('DOING_AJAX')) {
        $gateways[] = 'WC_Gateway_Whatsapp';
        $gateways[] = 'WC_Gateway_Shoppe';
    }
    return $gateways;
}

add_action('plugins_loaded', 'init_manual_payment_gateways');

function init_manual_payment_gateways() {
    class WC_Gateway_Whatsapp extends WC_Payment_Gateway {

        public function __construct() {
            $this->id = 'whatsapp';
            $this->has_fields = false;
            $this->supports = array('manual_orders');
            $this->method_title = 'Whatsapp';
            $this->method_description = 'Pay via Whatsapp';

            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');
            $this->method_description = $this->get_option('description');

            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => __('Enable/disable', 'woocommerce'),
                    'label'       => __('Enable Whatsapp payments', 'woocommerce'),
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'yes'
                ),
                'title' => array(
                    'title'       => __('Title', 'woocommerce'),
                    'type'        => 'text',
                    'description' => __('Enter a title for this payment method', 'woocommerce'),
                    'default'     => __('Whatsapp', 'woocommerce'),
                    'desc_tip'    => true
                ),
                'description' => array(
                    'title'       => __('Description', 'woocommerce'),
                    'type'        => 'textarea',
                    'description' => __('Enter a description for this payment method', 'woocommerce'),
                    'default'     => __('Pay via Whatsapp', 'woocommerce'),
                    'desc_tip'    => true
                )
            );
        }

        public function process_payment($order_id) {
            $order = wc_get_order($order_id);

            $order->update_status('on-hold', __('Awaiting payment via Whatsapp.', 'woocommerce'));

            WC()->cart->empty_cart();

            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
        }

        public function payment_fields() {
            if ($this->description) {
                echo wpautop(wptexturize($this->description));
            }
        }
    }

    class WC_Gateway_Shoppe extends WC_Payment_Gateway {

        public function __construct() {
            $this->id = 'shoppe';
            $this->has_fields = false;
            $this->supports = array('manual_orders');
            $this->method_title = 'Pay via Shoppe';
            $this->method_description = 'Pay via Shoppe';

            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');
            $this->method_description = $this->get_option('description');

            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }

        public function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title'       => __('Enable/disable', 'woocommerce'),
                    'label'       => __('Enable Shoppe payments', 'woocommerce'),
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'yes'
                ),
                'title' => array(
                    'title'       => __('Title', 'woocommerce'),
                    'type'        => 'text',
                    'description' => __('Enter a title for this payment method', 'woocommerce'),
                    'default'     => __('Pay via Shoppe', 'woocommerce'),
                    'desc_tip'    => true
                ),
                'description' => array(
                    'title'       => __('Description', 'woocommerce'),
                    'type'        => 'textarea',
                    'description' => __('Enter a description for this payment method', 'woocommerce'),
                    'default'     => __('Pay via Shoppe', 'woocommerce'),
                    'desc_tip'    => true
                )
            );
        }

        public function process_payment($order_id) {
            $order = wc_get_order($order_id);

            $order->update_status('on-hold', __('Awaiting payment via Shoppe.', 'woocommerce'));

            WC()->cart->empty_cart();

            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
        }

        public function payment_fields() {
            if ($this->description) {
                echo wpautop(wptexturize($this->description));
            }
        }
    }
}

?>

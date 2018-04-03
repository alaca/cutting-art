<?php 
/**
* @package     Cutting Art Plugin
* @author      Ante Laca <ante.laca@gmail.com>
* @copyright   2018 Roi Holdings
*/

namespace CuttingArt;

class CTA extends \PriorityAPI\API
{
    // instance
    private static $instance;

    public static $parameters = [];

    // initialize
    public static function init()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();    
        }
        
        return static::$instance;
    }

    private function __construct()
    {
    }

    public function run()
    {
        return is_admin() ? $this->backend() : $this->frontend();
    }

    // frontend part
    private function frontend()
    {
        // if we are not on login page
        if ($GLOBALS['pagenow'] != 'wp-login.php') {

            // user must be logged in as administrator or customer
            if (current_user_can('administrator') || current_user_can('customer')) {

                // include parameters to product page
                add_action('woocommerce_before_add_to_cart_button', function(){
                    include CTA_ADMIN_DIR . 'product_parameters.php';
                });    
                
                // insert selected data into cart
                add_filter('woocommerce_add_cart_item_data', function($cart_data, $product_id, $variation_id){

                    $parameters = $this->getProductParameters($product_id);

                    foreach ($parameters as $parameter) {

                        // cannot pass id as is because its changed
                        if (isset($_POST['cta_parameters'][$parameter->id])) {
                            $cart_data[$parameter->priority_id] = filter_var($_POST['cta_parameters'][$parameter->id], FILTER_SANITIZE_STRING);
                        }

                    }

                    return $cart_data;

                }, 10, 3);

                // show selected parameters in cart                 
                add_filter('woocommerce_get_item_data', function ($item_data, $cart_item) {

                    $parameters = $this->getProductParameters($cart_item['product_id']);

                    foreach ($parameters as $parameter) {

                        if (isset($cart_item[$parameter->priority_id])) {
                            
                            $item_data[] = array(
                                'key' => $parameter->name,
                                'value' => wc_clean($cart_item[$parameter->priority_id]),
                                'display' => '',
                            );
                         
                        }

                    }

                    return $item_data;

                }, 10, 2);


                // add parameter values to order                
                add_action( 'woocommerce_checkout_create_order_line_item', function($item, $cart_item_key, $values, $order) {

                    $parameters = $this->getProductParameters($values['product_id']);

                    foreach ($parameters as $parameter) {

                        if (isset($values[$parameter->priority_id])) {
                            $item->add_meta_data($parameter->name, $values[$parameter->priority_id]);
                        }

                    }

                }, 10, 4);


            } else {

                // unregistered user
                $this->redirectToLoginPage();

            }

        }

    }

    // backend part
    private function backend()
    {
   
        add_action('admin_init', function() {

            // check if current user is an admin
            if ( ! current_user_can('administrator')) {
                $this->redirectToLoginPage();
            }

            // enqueue admin styles and scripts
            wp_enqueue_style('cta-admin-css', CTA_ASSET_URL . 'style.css');   
            wp_enqueue_script('cta-admin-js', CTA_ASSET_URL . 'admin.js', ['jquery']);
            wp_localize_script('cta-admin-js', 'CTA', [
                'delete' => __('Delete', 'cta'),
                'remove' => __('Remove', 'cta'),
                'save' => __('Save', 'cta'),
                'add' => __('Add', 'cta'),
                'removeParameter' => __('Are you sure you want to remove parameter', 'cta'),
                'removeValue' => __('Are you sure you want to remove value', 'cta'),
                'assetUrl' => CTA_ASSET_URL,
            ]);
 

            // add parameters tab to product page
            add_filter('woocommerce_product_data_tabs', function($tabs) {

                $tabs['cta-parameters'] = [
                    'label' => __('Parameters', 'cta'),
                    'target' => 'cta_parameters',
                    //'class' => ['show_if_simple']
                ];

                return $tabs;
                
            });

            // add parameters panel to product page
            add_action('woocommerce_product_data_panels', function() {

                include CTA_ADMIN_DIR . 'panel.php';

            });          

                    
        });

        // admin page
        add_action('admin_menu', function(){

            include CTA_CLASSES_DIR . 'listtable.php';

            add_menu_page(CTA_PLUGIN_NAME, CTA_PLUGIN_NAME, 'manage_options', CTA_PLUGIN_ADMIN_URL, function() { 

                // admin pages
                switch($this->get('tab')) {

                    case 'add_parameter':

                        include CTA_ADMIN_DIR . 'add_parameter.php';

                        break;

                    case 'edit_parameter':

                        $data = $GLOBALS['wpdb']->get_row('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters WHERE id = ' . intval($this->get('parameter')));

                        if (empty($data)) {
                            wp_redirect('admin.php?page=' . CTA_PLUGIN_ADMIN_URL);
                            exit;
                        }

                        include CTA_ADMIN_DIR . 'edit_parameter.php';

                        break;

                    case 'default_values':

                        $parameter = $GLOBALS['wpdb']->get_row('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters WHERE id = ' . intval($this->get('parameter')));

                        if (empty($parameter) || $parameter->type != 'dropdown') {
                            wp_redirect('admin.php?page=' . CTA_PLUGIN_ADMIN_URL);
                            exit;
                        }
                        
                        include CTA_ADMIN_DIR . 'defaults_values.php';

                        break;


                    case 'conversion-types':

                        include CTA_ADMIN_DIR . 'conversion_types.php';

                        break;

                    case 'add-type':

                        include CTA_ADMIN_DIR . 'add_type.php';

                        break;

                    case 'edit-type':

                        $data = $GLOBALS['wpdb']->get_row('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_types WHERE id = ' . intval($this->get('type')));

                        if (empty($data)) {
                            wp_redirect('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-types');
                            exit;
                        }

                        include CTA_ADMIN_DIR . 'edit_type.php';

                        break;

                    case 'conversion-table':

                        include CTA_ADMIN_DIR . 'conversion_table.php';

                        break;

                    case 'add-conversion':

                        include CTA_ADMIN_DIR . 'add_conversion.php';

                        break;

                    case 'edit-conversion':

                        $data = $GLOBALS['wpdb']->get_row('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_conversions WHERE id = ' . intval($this->get('id')));

                        if (empty($data)) {
                            wp_redirect('admin.php?page=' . CTA_PLUGIN_ADMIN_URL . '&tab=conversion-table');
                            exit;
                        }

                        include CTA_ADMIN_DIR . 'edit_conversion.php';

                        break;

                    default: 

                        include CTA_ADMIN_DIR . 'parameters.php';
                }

            });
            
        });

        /**
         * AJAX
         */

        // ajax add parameter       
        add_action('wp_ajax_cta_add_param', function() {

            $parameter = $this->getParameter($this->post('param'));

            $meta_value = ($parameter->type == 'dropdown') ? __('Irrelevant', 'cta') : null;

            $status = $GLOBALS['wpdb']->insert($GLOBALS['wpdb']->prefix . 'cta_parameters_meta', [
                'param_id' => $this->post('param'),
                'product_id' => $this->post('post'),
                'meta_value' => $meta_value
            ]);

            // user inserted
            delete_post_meta($this->post('post'), 'cta_param_removed_' . $this->post('param'));


            include CTA_ADMIN_DIR . 'ajax_panel.php';

            exit;

        });


        // ajax save parameter value       
        add_action('wp_ajax_cta_save_value', function() {

            $status = $GLOBALS['wpdb']->insert($GLOBALS['wpdb']->prefix . 'cta_parameters_meta', [
                'param_id' => $this->post('param'),
                'product_id' => $this->post('post'),
                'meta_value' => $this->post('meta')
            ]);

            // send response
            wp_send_json([
                'status' => $status, 
                'id' => $GLOBALS['wpdb']->insert_id,
                'error' => $GLOBALS['wpdb']->last_error
            ]);

        });


        // ajax remove parameter value       
        add_action('wp_ajax_cta_remove_value', function() {

            $status = $GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters_meta WHERE id = ' . intval($this->post('id')));

            wp_send_json(['status' => $status, 'error' => $GLOBALS['wpdb']->last_error]);

        });

        // ajax remove parameter group       
        add_action('wp_ajax_cta_remove_param', function() {

            $id = intval($this->post('id'));
            $post_id = intval($this->post('post_id'));

            $status = $GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters_meta WHERE param_id = ' . $id);

            // set it as removed
            add_post_meta($post_id, 'cta_param_removed_' . $id, true);

            wp_send_json(['status' => ($status === false) ? 0 : 1, 'error' => $GLOBALS['wpdb']->last_error]);

        });



        /**
         * CRUD
         */

        // insert parameter
        if ($this->post('insert_param') && wp_verify_nonce($this->post('cta'), 'insert_param')) {

            $GLOBALS['wpdb']->insert($GLOBALS['wpdb']->prefix . 'cta_parameters', [
                'priority_id' => $this->post('param_priority'),
                'name' => $this->post('param_name'),
                'type' => $this->post('param_type'),
                'use_defaults' => intval($this->post('param_defaults')),
                'use_foreach' => $this->post('param_foreach') ? 1 : 0,
                'use_conversion' => $this->post('param_conversion') ? 1 : 0
            ]);

            $this->notify(__('Parameter inserted', 'cta'));

        }

        // edit parameter
        if ($this->post('edit_param') && wp_verify_nonce($this->post('cta'), 'edit_param')) {

            $GLOBALS['wpdb']->update($GLOBALS['wpdb']->prefix . 'cta_parameters', [
                'priority_id' => $this->post('param_priority'),
                'name' => $this->post('param_name'),
                'type' => $this->post('param_type'),
                'use_defaults' => intval($this->post('param_defaults')),
                'use_foreach' => $this->post('param_foreach') ? 1 : 0,
                'use_conversion' => $this->post('param_conversion') ? 1 : 0
            ], [
                'id' => intval($this->post('param_id'))
            ]);

            $this->notify(__('Parameter edited', 'cta'));

        }

        // delete parameter
        if ($this->get('delete') && wp_verify_nonce($this->get('cta'), 'delete_param')) {

            $GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters WHERE id = ' . intval($this->get('delete')));
            $GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'cta_defaults_meta WHERE param_id = ' . intval($this->get('delete')));

            $this->notify(__('Parameter deleted', 'cta'));

        }


        // insert defaults value
        if ($this->post('insert_defaults_value') && wp_verify_nonce($this->post('cta'), 'insert_defaults_value')) {

            $GLOBALS['wpdb']->insert($GLOBALS['wpdb']->prefix . 'cta_defaults_meta', [
                'param_id' => $this->post('param_id'),
                'meta_value' => $this->post('param_value')
            ]);

            $this->notify(__('Value added', 'cta'));

        }
        

        // delete defaults
        if ($this->get('delete') && wp_verify_nonce($this->get('cta'), 'delete_default_value')) {

            $GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'cta_defaults_meta WHERE id = ' . intval($this->get('delete')));

            $this->notify(__('Value deleted', 'cta'));

        }

        // add conversion type
        if ($this->post('insert_conversion_type') && wp_verify_nonce($this->post('cta'), 'insert_conversion_type')) {

            $status = $GLOBALS['wpdb']->insert($GLOBALS['wpdb']->prefix . 'cta_types', [
                'name' => $this->post('conversion_type_name'),
                'code' => $this->post('conversion_type_code')
            ]);

            $message = ($status) ? __('Conversion type added', 'cta') : __('Something went wrong, Conversion type not added', 'cta');

            $this->notify($message);

        }

        // edit conversion type
        if ($this->post('edit_conversion_type') && wp_verify_nonce($this->post('cta'), 'edit_conversion_type')) {

            $status = $GLOBALS['wpdb']->update($GLOBALS['wpdb']->prefix . 'cta_types', [
                'name' => $this->post('conversion_type_name'),
                'code' => $this->post('conversion_type_code')
            ], [
                'id' => intval($this->post('conversion_type_id'))
            ]);


            $message = ($status) ? __('Conversion type edited', 'cta') :  __('Something went wrong, conversion type not edited', 'cta');
            

            $this->notify($message, $status ? 'success' : 'error');

        }

        // delete conversion type
        if ($this->get('delete') && wp_verify_nonce($this->get('cta'), 'delete_conversion_type')) {

            $GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'cta_types WHERE id = ' . intval($this->get('delete')));

            $this->notify(__('Conversion type deleted', 'cta'));

        }


        // add conversion
        if ($this->post('insert_conversion') && wp_verify_nonce($this->post('cta'), 'insert_conversion')) {

            $status = $GLOBALS['wpdb']->insert($GLOBALS['wpdb']->prefix . 'cta_conversions', [
                'type_id' => $this->post('conversion_type'),
                'circumference' => $this->post('conversion_circumference'),
                'diameter' => $this->post('conversion_diameter')
            ]);

            $message = ($status) ? __('Conversion added', 'cta') : __('Something went wrong, Conversion  not added', 'cta');

            $this->notify($message);

        }

        // edit conversion
        if ($this->post('edit_conversion') && wp_verify_nonce($this->post('cta'), 'edit_conversion')) {

            $status = $GLOBALS['wpdb']->update($GLOBALS['wpdb']->prefix . 'cta_conversions', [
                'type_id' => $this->post('conversion_type'),
                'circumference' => $this->post('conversion_circumference'),
                'diameter' => $this->post('conversion_diameter')
            ], [
                'id' => intval($this->post('conversion_id'))
            ]);


            $message = ($status) ? __('Conversion edited', 'cta') :  __('Something went wrong, conversion not edited', 'cta');
            

            $this->notify($message, $status ? 'success' : 'error');

        }

        // delete conversion
        if ($this->get('delete') && wp_verify_nonce($this->get('cta'), 'delete_conversion')) {

            $GLOBALS['wpdb']->query('DELETE FROM ' . $GLOBALS['wpdb']->prefix . 'cta_conversions WHERE id = ' . intval($this->get('delete')));

            $this->notify(__('Conversion deleted', 'cta'));

        }

    }

    /**
     * Redirect to login page
     *
     * @return void
     */
    public function redirectToLoginPage()
    {
        wp_logout(); // logout current user
        wp_redirect(wp_login_url());
        exit;
    }


    /**
     * Get all parameters
     *
     * @return array
     */
    public static function getParameters()
    {
        return $GLOBALS['wpdb']->get_results('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters');
    }

    
    /**
     * Get  parameter
     *
     * @return array
     */
    public function getParameter($id)
    {
        return $GLOBALS['wpdb']->get_row('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters WHERE id = ' . intval($id));
    }


    /**
     * Get parameter by name
     *
     * @return array
     */
    public static function getParameterByName($name)
    {
        return $GLOBALS['wpdb']->get_row('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters WHERE name = "' . esc_sql($name) , '"');
    }


    /**
     * Get parameters meta for poroduct
     *
     * @param [int] $product_id
     * @param [int] $param_id
     * @return array
     */
    public function getParametersMeta($product_id, $param_id) 
    {

        return $GLOBALS['wpdb']->get_results('
            SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_parameters_meta 
            WHERE product_id = ' . intval($product_id) . ' 
            AND param_id = ' . intval($param_id)
        );

    }



    public function getProductParameters($id) 
    {

        if (isset(static::$parameters[$id])) {
            return static::$parameters[$id];
        }

        $parameters = static::getParameters();

        foreach($parameters as $i => $parameter) {

            if ($parameter->use_foreach) {

                // check if parameter is removed
                if (get_post_meta($id, 'cta_param_removed_' . $parameter->id, true)) {
                    unset($parameters[$i]);
                    continue;
                }

                if($parameter->type == 'dropdown') {

                    $meta = $this->getParametersMeta($id, $parameter->id);

                    // check parameter data
                    if (empty($meta)) {

                        $default = $this->getDefaultsMeta($parameter->id);

                        // remove parameter because its empty
                        if(empty($default)) {
                            unset($parameters[$i]);
                            continue;
                        }

                        // insert default data
                        foreach($default as $data) {

                            $GLOBALS['wpdb']->insert($GLOBALS['wpdb']->prefix . 'cta_parameters_meta', [
                                'param_id'   => $parameter->id,
                                'product_id' => $id,
                                'meta_value' => $data->meta_value
                            ]);
                
                        }

                    }
                    
                }

            } else {

                $meta = $this->getParametersMeta($id, $parameter->id);

                // check parameter data
                if (empty($meta)) {
                    unset($parameters[$i]);
                    continue;
                }

            }
            
        }


        static::$parameters[$id] = $parameters;
       

        return static::$parameters[$id];

    }


    /**
     * Get default data for parameter
     *
     * @param [int] $param_id
     * @return array
     */
    public function getDefaultsMeta($param_id)
    {
        return $GLOBALS['wpdb']->get_results('SELECT id, meta_value FROM ' . $GLOBALS['wpdb']->prefix . 'cta_defaults_meta WHERE param_id = ' . intval($param_id));
    }


    /**
     * Get conversion types
     *
     * @return void
     */
    public function getConversionTypes()
    {
        return $GLOBALS['wpdb']->get_results('SELECT * FROM ' . $GLOBALS['wpdb']->prefix . 'cta_types');  
    }


}
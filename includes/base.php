<?php 
/**
* @package     Cutting Art Plugin
* @author      Ante Laca <ante.laca@gmail.com>
* @copyright   2018 Roi Holdings
*/

namespace CuttingArt;

class Base
{
    // instance
    private static $instance;

    // required dependencies
    private $dependencies = [
        'woocommerce/woocommerce.php',
        'priority18-api/priority18-api.php',
        'priority-woo-api/priority-woo-api.php'
    ];

    /**
     * Initialize
     *
     * @return void
     */
    public static function init()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();    
        }
        
        return static::$instance;
    }

    private function __construct()
    {
        // activation
        register_activation_hook(CTA_SELF, [$this, 'activate']);
        // deactivation
        register_deactivation_hook(CTA_SELF, [$this, 'deactivate']);
        // load language
        load_plugin_textdomain('cta', false, plugin_basename(CTA_DIR) . '/languages');

    }

    /**
     * Plugin activation
     *
     * @return void
     */
    public function activate()
    {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $parameters = $GLOBALS['wpdb']->prefix . 'cta_parameters'; 
        $param_meta = $GLOBALS['wpdb']->prefix . 'cta_parameters_meta'; 
        $defaults_meta = $GLOBALS['wpdb']->prefix . 'cta_defaults_meta'; 
        $conversion = $GLOBALS['wpdb']->prefix . 'cta_conversions'; 
        $types = $GLOBALS['wpdb']->prefix . 'cta_types'; 
         
        $table1 = "CREATE TABLE $parameters (
            id  INT AUTO_INCREMENT,
            priority_id VARCHAR(32),
            name VARCHAR(32),
            type VARCHAR(32),
            use_foreach TINYINT,
            use_conversion TINYINT,
            use_defaults INT,
            PRIMARY KEY  (id)
        )";

        dbDelta($table1);

        $table2 = "CREATE TABLE $param_meta (
            id  INT AUTO_INCREMENT,
            param_id INT,
            product_id INT,
            meta_name VARCHAR(32),
            meta_value VARCHAR(32),
            PRIMARY KEY  (id)
        )";
            
        dbDelta($table2);

        $table3 = "CREATE TABLE $conversion (
            id  INT AUTO_INCREMENT,
            type_id INT,
            circumference DECIMAL(4,2),
            diameter VARCHAR(6),
            PRIMARY KEY  (id)
        )";


        dbDelta($table3);


        $table4 = "CREATE TABLE $types (
            id  INT AUTO_INCREMENT,
            code VARCHAR(6),
            name VARCHAR(32),
            PRIMARY KEY  (id)
        )";

        dbDelta($table4);

        $table5 = "CREATE TABLE $defaults_meta (
            id  INT AUTO_INCREMENT,
            param_id INT,
            meta_value VARCHAR(32),
            PRIMARY KEY  (id)
        )";

        dbDelta($table5);

    }

    /**
     * Plugin deactivation
     *
     * @return void
     */
    public function deactivate()
    {
        /*
        $GLOBALS['wpdb']->query('DROP TABLE IF EXISTS ' . $GLOBALS['wpdb']->prefix . 'cta_parameters;');
        $GLOBALS['wpdb']->query('DROP TABLE IF EXISTS ' . $GLOBALS['wpdb']->prefix . 'cta_parameters_meta;');
        $GLOBALS['wpdb']->query('DROP TABLE IF EXISTS ' . $GLOBALS['wpdb']->prefix . 'cta_defaults_meta;');
        $GLOBALS['wpdb']->query('DROP TABLE IF EXISTS ' . $GLOBALS['wpdb']->prefix . 'cta_conversions;');
        $GLOBALS['wpdb']->query('DROP TABLE IF EXISTS ' . $GLOBALS['wpdb']->prefix . 'cta_types;');
        */
    }

    /**
     * Run 
     *
     * @return void
     */
    public function run()
    {
        // hook up
        add_action('plugins_loaded', function() {

            // check if we have all we need?
            if (empty($missing = $this->checkDependencies())) {

                require CTA_CLASSES_DIR . 'cta.php';
                // run Forrest, run
                CTA::init()->run();

            } else {

                // display error message about missing dependencies
                foreach($missing as $name) {
                    add_action('admin_notices', function() use ($name) {
                        printf('<div class="notice notice-error"><p>%s</p></div>', sprintf(__('In order to use Cutting Art plugin, %s must be activated', 'cta'), $name));
                    });
                }
                
            }

        });

    }


    /**
     * Check for dependencies
     *
     * @return array
     */
    public function checkDependencies()
    {
        include_once(trailingslashit(ABSPATH) . 'wp-admin/includes/plugin.php'); 

        // get active plugins
        $plugins = get_option('active_plugins');

        $missing = [];

        // check dependencies
        foreach ($this->dependencies as $dependency) {

            if (!in_array($dependency, $plugins)) {
                $data = get_plugin_data(trailingslashit(WP_PLUGIN_DIR) . $dependency);
                $missing[] = $data['Name'];
            }

        }

        return $missing;
    }

}
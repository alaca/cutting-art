<?php
/**
* @package     Cutting Art Plugin
* @author      Ante Laca <ante.laca@gmail.com>
* @copyright   2018 Roi Holdings
*
* @wordpress-plugin
* Plugin Name: Cutting Art
* Plugin URI: http://www.roi-holdings.com
* Description: Cutting Art
* Version: 1.0
* Author: Roi Holdings
* Author URI: http://www.roi-holdings.com
* Licence: GPLv2
* Text Domain: cta
* Domain Path: languages  
* 
*/

namespace CuttingArt;

define('CTA_VERSION'       , '1.0');
define('CTA_SELF'          , __FILE__);
define('CTA_URI'           , plugin_dir_url(__FILE__));
define('CTA_DIR'           , plugin_dir_path(__FILE__)); 
define('CTA_ASSET_DIR'     , trailingslashit(CTA_DIR)    . 'assets/');
define('CTA_ASSET_URL'     , trailingslashit(CTA_URI)    . 'assets/');
define('CTA_INCLUDES_DIR'  , trailingslashit(CTA_DIR)    . 'includes/');
define('CTA_CLASSES_DIR'   , trailingslashit(CTA_DIR)    . 'includes/classes/');
define('CTA_ADMIN_DIR'     , trailingslashit(CTA_DIR)    . 'includes/admin/');

// define plugin name and plugin admin url
define('CTA_PLUGIN_NAME'      , 'Cutting Art');
define('CTA_PLUGIN_ADMIN_URL' , sanitize_title(CTA_PLUGIN_NAME));

require CTA_INCLUDES_DIR . 'base.php';

Base::init()->run();

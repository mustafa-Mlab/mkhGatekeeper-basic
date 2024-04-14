<?php
/**
 * @link    http://mkhossain.com/development/plugins/mkhGatekeeper-Basic
 * @package mkhGatekeeper_Basic
 * @since   1.0.0
 * @version 1.0.1
 * 
 * @wordpress-plugin
 * Plugin Name: MKH Gate Keeper Basic
 * Plugin URI: http://mkhossain.com/development/plugins/mkhGatekeeper-Basic
 * Description: Control content visibility on your WordPress site. Restrict access to specific posts based on user login or logout status. (Future versions will offer user role-based access control.) 
 * Author: MD Mustafa Kamal Hossain	
 * Version: 1.0.0
 * Author URI: http://mkhossain.com
 * Text Domain: mkhGatekeeper-Basic
 * License: GPLv2 or later
 *  License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /languages
 */


 // Make sure we don't expose any info if called directly
if ( ! defined( 'ABSPATH' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}


// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

if ( ! defined( 'MKH_GATE_KEEPTE_FILE' ) ) {
  define( 'MKH_GATE_KEEPTE_FILE', __FILE__ );
}

if ( ! defined( 'MKH_GATE_KEEPER_PATH' ) ) {
  define( 'MKH_GATE_KEEPER_PATH', plugin_dir_path( MKH_GATE_KEEPTE_FILE ));
}

if ( ! defined( 'MKH_GATE_KEEPER_URL' ) ) {
  define( 'MKH_GATE_KEEPER_URL',  plugin_dir_url( MKH_GATE_KEEPTE_FILE ));
}


/**
 * Currently plugin version.
 */
define( 'MKH_GATE_KEEPER_VERSION', '1.0.0' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activator.php
 */
function activate_mkh_gate_keeper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/activator.php';
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivator.php
 */
function deactivate_mkh_gate_keeper() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/deactivator.php';
}

/**
 * The code that runs during plugin deletion.
 * This action is documented in includes/deletion.php
 */
function mkh_gate_keeper_uninstall() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/deletion.php';
}

register_activation_hook( __FILE__, 'activate_mkh_gate_keeper' );
register_deactivation_hook( __FILE__, 'deactivate_mkh_gate_keeper' );
register_uninstall_hook(__FILE__, 'mkh_gate_keeper_uninstall');

// Include necessary WordPress functions
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');



/** Add css and javascript file for frontend */
function enqueue_access_code_script() {
  wp_enqueue_style('mkh-gate-keeper-styles', MKH_GATE_KEEPER_URL . 'assets/fn-css.css', array(), '1.0');
  wp_enqueue_script('mkh-gate-keeper-script', MKH_GATE_KEEPER_URL . 'assets/mkh-gate-keeper.js' , array('jquery'), '1.0', true);

  // Localize the script to make 'ajaxurl' available in JavaScript
  wp_localize_script('mkh-gate-keeper-script', 'essentialData', array(
    'ajaxurl' => admin_url('admin-ajax.php')
  ));
}
add_action('wp_enqueue_scripts', 'enqueue_access_code_script');

function enqueue_dataTables() {
  wp_enqueue_style('admin-part-css', MKH_GATE_KEEPER_URL . 'assets/admin-part.css', array(), '1.0');
  wp_enqueue_script('jquery');
  wp_enqueue_script('mkh-gate-keeper-admin', MKH_GATE_KEEPER_URL . 'assets/admin-part.js' , array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'enqueue_dataTables');
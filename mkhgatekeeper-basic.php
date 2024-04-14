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

// Define plugin options name
define( 'MKGK_OPTIONS', 'mkhgatekeeper_basic_options' );

// Register settings for mkhgatekeeper_basic options
register_setting( 'mkhgatekeeper_basic_options', MKGK_OPTIONS );


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
// function enqueue_access_code_script() {
//   wp_enqueue_style('mkh-gate-keeper-styles', MKH_GATE_KEEPER_URL . 'assets/fn-css.css', array(), '1.0');
//   wp_enqueue_script('mkh-gate-keeper-script', MKH_GATE_KEEPER_URL . 'assets/mkh-gate-keeper.js' , array('jquery'), '1.0', true);

//   // Localize the script to make 'ajaxurl' available in JavaScript
//   wp_localize_script('mkh-gate-keeper-script', 'essentialData', array(
//     'ajaxurl' => admin_url('admin-ajax.php')
//   ));
// }
// add_action('wp_enqueue_scripts', 'enqueue_access_code_script');

// function enqueue_dataTables() {
//   wp_enqueue_style('admin-part-css', MKH_GATE_KEEPER_URL . 'assets/admin-part.css', array(), '1.0');
//   wp_enqueue_script('jquery');
//   wp_enqueue_script('mkh-gate-keeper-admin', MKH_GATE_KEEPER_URL . 'assets/admin-part.js' , array('jquery'), '1.0', true);
// }
// add_action('admin_enqueue_scripts', 'enqueue_dataTables');



// Add settings link to plugin list
add_action( 'admin_menu', 'mkhgatekeeper_basic_settings_page' );

function mkhgatekeeper_basic_settings_page() {
  add_options_page(
    'mkhGatekeeper-Basic Settings', // Page title
    'mkhGatekeeper-Basic', // Menu title
    'manage_options', // Capability required
    'mkhgatekeeper-basic', // Menu slug
    'mkhgatekeeper_basic_settings_content' // Callback function
  );
}

// Function to display settings page content
function mkhgatekeeper_basic_settings_content() {
    // Get saved options
    $mkgk_options_value = get_option( MKGK_OPTIONS );
    
    $selected_post_types = (array_key_exists('post_types', $mkgk_options_value))? $mkgk_options_value['post_types'] : array();
    $selected_user_roles = (array_key_exists('user_roles', $mkgk_options_value))? $mkgk_options_value['user_roles'] : array(); 
    
  
    // Get all available post types
    $post_types = get_post_types( '', 'objects' );
  
    // Get all user roles
    $user_roles = get_editable_roles();
  
    ?>
    <div class="wrap">
      <h1>mkhGatekeeper-Basic Settings</h1>
      <form method="post" action="options.php">
        <?php settings_fields( 'mkhgatekeeper_basic_options' ); ?>
  
        <h2>Post Types</h2>
        <p>Select the post types where users need to be logged in to view content:</p>
        <?php foreach ($post_types as $post_type) : ?>
          <p>
            <input type="checkbox" id="mkhgk_post_type_<?php echo $post_type->name; ?>" name="mkhgatekeeper_basic_options[post_types][]" value="<?php echo $post_type->name; ?>" <?php echo in_array($post_type->name, $selected_post_types) ? 'checked' : ''; ?> />
            <label for="mkhgk_post_type_<?php echo $post_type->name; ?>"><?php echo $post_type->label; ?></label>
          </p>
        <?php endforeach; ?>
  
        <h2>User Roles</h2>
        <p>Select the user roles that can access content without login (if a post type is restricted):</p>
        <?php foreach ($user_roles as $role => $role_data) : ?>
          <p>
            <input type="checkbox" id="mkhgk_user_role_<?php echo $role; ?>" name="mkhgatekeeper_basic_options[user_roles][]" value="<?php echo $role; ?>" <?php echo in_array($role, $selected_user_roles) ? 'checked' : ''; ?> />
            <label for="mkhgk_user_role_<?php echo $role; ?>"><?php echo $role_data['name']; ?></label>
          </p>
        <?php endforeach; ?>
  
        <?php submit_button(); ?>
      </form>
    </div>
    <?php
  }


  function mkhgatekeeper_add_meta_box() {
    $mkgk_options_value = get_option( MKGK_OPTIONS );
    
    $post_types = (array_key_exists('post_types', $mkgk_options_value))? $mkgk_options_value['post_types'] : array();
  
    // Loop through each selected post type
    foreach ($post_types as $post_type) {
      add_meta_box(
        'mkhgatekeeper_login_meta_box', // Unique ID
        'Login Required', // Title displayed
        'mkhgatekeeper_meta_box_callback', // Callback function to display content
        $post_type, // Post type where the meta box shows
        'side', // Placement (normal or side)
        'high' // Priority level (high, default, low)
      );
    //   add_action( "quick_edit_custom_box_$post_type", 'mkhgatekeeper_quick_edit_callback' );
    }
  }
  
  add_action( 'add_meta_boxes', 'mkhgatekeeper_add_meta_box' );

  function mkhgatekeeper_meta_box_callback( $post ) {
    $login_required = get_post_meta( $post->ID, 'mkhgatekeeper_login_required', true ); // Get existing value
  
    $checked = $login_required ? 'checked' : ''; // Set checkbox state
  
    wp_nonce_field( 'mkhgatekeeper_meta_nonce', 'mkhgatekeeper_meta_nonce' ); // Security nonce
  
    ?>
    <p>
      <label for="mkhgatekeeper_login_required">
        <input type="checkbox" id="mkhgatekeeper_login_required" name="mkhgatekeeper_login_required" value="1" <?php echo $checked; ?> />
        This content requires users to be logged in to view.
      </label>
    </p>
    <?php
  }

  function mkhgatekeeper_save_meta_box( $post_id ) {
    // Check if user has permission to edit post
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }
  
    // Check if the form was submitted
    if ( ! isset( $_POST[ 'mkhgatekeeper_meta_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'mkhgatekeeper_meta_nonce' ], 'mkhgatekeeper_meta_nonce' ) ) {
      return;
    }
  
    // Sanitize and update meta field data
    $login_required = isset( $_POST[ 'mkhgatekeeper_login_required' ] ) ? 1 : 0;
    update_post_meta( $post_id, 'mkhgatekeeper_login_required', $login_required );
  }
  
  add_action( 'save_post', 'mkhgatekeeper_save_meta_box' );

  function mkhgatekeeper_quick_edit_callback( $post_id ) {
    $login_required = get_post_meta( $post_id, 'mkhgatekeeper_login_required', true );
    $checked = $login_required ? 'checked' : '';
  
    ?>
    <div class="inline edit-col">
      <span class="title">Login Required</span>
      <div class="inline edit-group">
        <label>
          <input type="checkbox" name="mkhgatekeeper_login_required" value="1" <?php echo $checked; ?> />
          This content requires users to be logged in to view.
        </label>
      </div>
    </div>
    <?php
  }


  function mkhgatekeeper_login_redirect() {
    $mkgk_options_value = get_option( MKGK_OPTIONS );
    
    $post_types = (array_key_exists('post_types', $mkgk_options_value))? $mkgk_options_value['post_types'] : array();
    // Get current URL and parse it
    $url = add_query_arg( array(), get_permalink() ); // Get URL without query arguments
  
    // Get post data from URL
    $post = get_post( url_to_postid( $url ) );
  
    // Check if post exists and is of a selected post type
    if ( $post && in_array( $post->post_type, $post_types ) ) {
      // Check if the "Login Required" meta field is checked
      $login_required = get_post_meta( $post->ID, 'mkhgatekeeper_login_required', true );
  
      // Redirect if login required and user is not logged in
      if ( $login_required && ! is_user_logged_in() ) {
        $login_url = wp_login_url( $url ); // Login URL with redirect back to this page
        wp_redirect( $login_url );
        exit;
      }
    }
  }
  
  add_action( 'template_redirect', 'mkhgatekeeper_login_redirect' ); // Hook before loading content
  
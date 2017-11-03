<?php
/**
 *
 * Plugin main file
 *
 * @link              https://infinum.co/careers
 * @since             1.0.0
 * @package           Json_Post_Parser
 *
 * @wordpress-plugin
 * Plugin Name:       JSON post parser
 * Plugin URI:        http://infinum.co
 * Description:       Parse post and pages content as JSON and serve it in default REST endpoint.
 * Version:           1.0.0
 * Author:            Infinum
 * Author URI:        https://infinum.co/careers
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       json-post-parser
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

add_action( 'admin_init', 'json_wp_post_parser_add_activation_notice' );

/**
 * Add admin notice upon plugin activation
 *
 * @since 1.0.0
 */
function json_wp_post_parser_add_activation_notice() {
  add_action( 'admin_notices', 'json_wp_post_parser_activation_notice' );
}

/**
 * Custom activation notice
 *
 * @since 1.0.0
 */
function json_wp_post_parser_activation_notice() {
  $json_wp_post_parser_active = get_option( 'json_wp_post_parser_active' );

  if ( ! $json_wp_post_parser_active ) {
    update_option( 'json_wp_post_parser_active', true );
    ?>
      <div class="notice notice-success is-dismissible">
        <p><?php printf( esc_html__( 'If you want to update all your posts and pages, go to ', 'json-post-parser' ) . '<a href="%s">' . esc_html__( 'this page', 'json-post-parser' ) . '</a>', esc_url( admin_url( 'options-general.php?page=json_parser_posts' ) ) ); ?></p>
        </div>
    <?php
  }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-json-post-parser-activator.php
 */
function activate_json_wp_post_parser() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-json-post-parser-activator.php';
  Json_Post_Parser_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-json-post-parser-deactivator.php
 */
function deactivate_json_wp_post_parser() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-json-post-parser-deactivator.php';
  Json_Post_Parser_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_json_wp_post_parser' );
register_deactivation_hook( __FILE__, 'deactivate_json_wp_post_parser' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-json-post-parser.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_json_wp_post_parser() {
  $plugin = new Json_Post_Parser();
  $plugin->run();
}
run_json_wp_post_parser();

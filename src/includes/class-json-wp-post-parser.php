<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://infinum.co/careers
 * @since      1.0.0
 *
 * @package    Json_WP_Post_Parser
 * @subpackage Json_WP_Post_Parser/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Json_WP_Post_Parser
 * @subpackage Json_WP_Post_Parser/includes
 * @author     Infinum <info@infinum.co>
 */
class Json_WP_Post_Parser {

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      Json_WP_Post_Parser_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function __construct() {
    if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
      $this->version = PLUGIN_NAME_VERSION;
    } else {
      $this->version = '1.0.0';
    }

    $this->plugin_name = 'json-wp-post-parser';

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->register_rest_routes();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Json_WP_Post_Parser_Loader. Orchestrates the hooks of the plugin.
   * - Json_WP_Post_Parser_i18n. Defines internationalization functionality.
   * - Json_WP_Post_Parser_Admin. Defines all hooks for the admin area.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function load_dependencies() {
    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-json-wp-post-parser-loader.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-json-wp-post-parser-i18n.php';

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-json-wp-post-parser-admin.php';

    /**
     * The class responsible for REST architecture.
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-json-wp-post-parser-rest.php';

    $this->loader = new Json_WP_Post_Parser_Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Json_WP_Post_Parser_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    1.0.0
   * @access   private
   */
  private function set_locale() {
    $plugin_i18n = new Json_WP_Post_Parser_i18n();

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    1.0.0
   * @access   private
   */
  private function define_admin_hooks() {
    $plugin_admin = new Json_WP_Post_Parser_Admin( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'save_post', $plugin_admin, 'parse_content_to_json' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_posts_parse_page' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
  }

  /**
   * Register custom REST routes.
   *
   * @since    1.0.0
   * @access   private
   */
  private function register_rest_routes() {
    $plugin_rest = new Json_WP_Post_Parser_Rest( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'rest_api_init', $plugin_rest, 'api_fields_init' );
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    1.0.0
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     1.0.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     1.0.0
   * @return    Json_WP_Post_Parser_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     1.0.0
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

}

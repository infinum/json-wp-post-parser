<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://infinum.co/careers
 * @since      1.0.0
 *
 * @package    Json_Post_Parser
 * @subpackage Json_Post_Parser/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Json_Post_Parser
 * @subpackage Json_Post_Parser/admin
 * @author     Infinum <info@infinum.co>
 */
class Json_Post_Parser_Admin {

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string $plugin_name       The name of this plugin.
   * @param      string $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
      $this->plugin_name = $plugin_name;
      $this->version = $version;
  }

  /**
   * Add page that will display reparsed posts
   */
  public function add_posts_parse_page() {
    add_options_page(
      esc_html__( 'Parse posts', 'json-post-parser' ),
      esc_html__( 'Parse posts', 'json-post-parser' ),
      'manage_options',
      'json_parser_posts',
      array( $this, 'render_parse_posts_page' )
    );
  }

  /**
   * Page that is used as a placeholder to show processed posts.
   *
   * @since 1.0.0
   */
  public function render_parse_posts_page() {
    $post_types = array( 'post', 'page' );
    if ( has_filter( 'json_wp_post_parser_add_post_types' ) ) {
      $post_types = apply_filters( 'json_wp_post_parser_add_post_types', $post_types );
    }

    $all_posts_args = array(
        'post_type'      => $post_types,
        'post_status'    => 'publish',
        'posts_per_page' => 5000,
    );

    $all_posts = new WP_Query( $all_posts_args );
    $posts_array = [];

    if ( $all_posts->have_posts() ) {
      while ( $all_posts->have_posts() ) {
        $all_posts->the_post();
        $posts_array[] = get_the_ID();
      }
      wp_reset_postdata();
    }
    ?>
    <div class="wrap">
      <h2><?php esc_html_e( 'Resave posts', 'json-post-parser' ); ?></h2>
      <div class="info" style="margin-bottom:20px;"><?php esc_html_e( 'This will resave all your existing posts and pages, including any custom post type you might have.', 'json-post-parser' ); ?></div>
      <div class="processed-posts js-processed-posts" data-posts="<?php echo wp_kses_post( wp_json_encode( $posts_array ) ); ?>"></div>
      <button class="button button-primary js-start-post-resave"><?php esc_html_e( 'Start resaving', 'json-post-parser' ); ?></button>
    </div>
    <?php
  }

  /**
   * Parse post content and store it in the custom table
   *
   * @param int    $post_id Post ID.
   * @param object $post    Post object.
   * @param bool   $update  Whether this is an existing post being updated or not.
   * @since 1.0.0
   */
  public function parse_content_to_json( $post_id, $post, $update ) {
    error_log( print_r( $post_id, true ) );
    error_log( print_r( $post, true ) );
    error_log( print_r( $update, true ) );
    if ( $update ) { // Trigger only on post save or update, not on new post.
      global $wpdb;

      // Remove newlines. If we don't do this, json has tons of empty texts that notify the newlines.
      $post_content_lines = str_replace( array( "\r\n", "\r" ), "\n", apply_filters( 'the_content', $post->post_content ) );

      $lines = explode( "\n", $post_content_lines );
      $new_lines = array();

      foreach ( $lines as $i => $line ) {
        if ( ! empty( $line ) ) {
          $new_lines[] = trim( $line );
        }
      }

      $post_content = implode( $new_lines );

      $post_dom = new DOMDocument();
      $post_dom->loadHTML( $post_content );

      $dom_json = wp_json_encode( $this->element_to_obj( $post_dom->documentElement ) );

      $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->posts SET post_content_json = %s WHERE ID = %d", $dom_json, $post_id ) );
    }
  }

  /**
   * Traverse through post html to create a post JSON
   *
   * @param  object $element Post dom element.
   * @return string          Post as JSON.
   */
  public function element_to_obj( $element ) {
    // Document element doesn't like comments so we treat them separately.
    if ( $element->nodeType === XML_ELEMENT_NODE && $element->nodeName !== '#comment' ) {
      // Get all the html object tag names. E.g. div, h2, code etc.
      $node = $this->check_node_type( $element->nodeType );

      $obj = array(
          'node' => $node,
          'tag'  => $element->tagName,
      );

      // Check the attributes, if there are any.
      foreach ( $element->attributes as $attribute ) {
        $obj['attr'][ $attribute->name ] = $attribute->value;
      }

      foreach ( $element->childNodes as $sub_element ) { // Child nodes.
        $obj['child'][] = $this->element_to_obj( $sub_element );
      }
    } elseif ( $element->nodeType === XML_TEXT_NODE ) {
      $obj['node'] = 'text';
      $obj['text'] = $element->wholeText;
    } else {
      $obj['tag']  = 'html-comment-tag';
      $obj['html'] = $element->nodeValue;
    }

    return $obj;
  }

  /**
   * Check node type
   *
   * @param  int $node_type Node type number.
   * @return string         Type of node.
   */
  public function check_node_type( $node_type ) {
    switch ( $node_type ) {
      case XML_ELEMENT_NODE:
        $node_type = 'element';
        break;
      case XML_TEXT_NODE:
        $node_type = 'text';
        break;
      default:
        $node_type = 'element';
        break;
    }

    return $node_type;
  }

  /**
   * Register the JavaScript for the admin area.
   *
   * @param string $hook Page hook name.
   * @since 1.0.0
   */
  public function enqueue_scripts( $hook ) {
    if ( $hook === 'settings_page_json_parser_posts' ) {
      wp_enqueue_script( $this->plugin_name, plugins_url() . '/' . $this->plugin_name . '/assets/scripts/application.js', array( '' ), $this->version, false );
      wp_localize_script( $this->plugin_name, 'wpApiSettings', array(
          'root'       => esc_url_raw( rest_url() ),
          'nonce'      => wp_create_nonce( 'wp_rest' ),
          'processing' => esc_html__( 'Processing...', 'json-post-parser' ),
      ) );
    }
  }

}

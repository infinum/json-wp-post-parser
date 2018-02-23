<?php
/**
 * Autoloader
 *
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin.
 *
 * Used and modified code from https://github.com/tommcfarlin/namespaces-and-autoloading-in-wordpress
 * Props to tommcfarlin <tom@tommcfarlin.com>
 *
 * @link    https://infinum.co/careers
 * @since   1.0.0
 *
 * @package Json_WP_Post_Parser\Lib
 */

namespace Json_WP_Post_Parser\Lib;

spl_autoload_register( __NAMESPACE__ . '\\autoloader' );

/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin by looking at the $class_name parameter being passed as an argument.
 *
 * @param string $class_name The fully-qualified name of the class to instantiate.
 */
function autoloader( $class_name ) {
  $file_path = explode( '\\', $class_name );

  if ( isset( $file_path[ count( $file_path ) - 1 ] ) ) {
    $class_file = strtolower(
      $file_path[ count( $file_path ) - 1 ]
    );
    // The classname has an underscore, so we need to replace it with a hyphen for the file name.
    $class_file = str_ireplace( '_', '-', $class_file );
    $class_file = "class-$class_file.php";
  }

  $full_path = trailingslashit(
    dirname(
      dirname( __FILE__ )
    )
  );

  $file_count = count( $file_path );
  for ( $i = 1; $i < $file_count - 1; $i++ ) {
    $dir        = str_ireplace( '_', '-', strtolower( $file_path[ $i ] ) );
    $full_path .= trailingslashit( $dir );
  }

  $full_path .= $class_file;

  // Now we include the file.
  if ( file_exists( $full_path ) ) {
    require_once $full_path;
  }
}

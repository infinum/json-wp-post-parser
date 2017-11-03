<?php
/**
 * Class Parsed_Content
 *
 * @package Json_Wp_Post_Parser
 */

/**
 * Class that tests for parsed content
 */
class Parsed_Content extends WP_UnitTestCase {
  /**
   * Test the content
   *
   * Test if the given HTML parses to a correct JSON.
   *
   * @since 1.0.0
   */
  function test_html_content() {

    $string = 'Unit tests are sweet';

    $this->assertEquals( 'Failing Unit tests are sad', $string );
  }
}

<?php
/**
 * Class Parsed_Content
 *
 * @package Json_Wp_Post_Parser
 */

use Json_WP_Post_Parser\Admin;

/**
 * Class that tests for parsed content
 */
class Parsed_Content extends WP_UnitTestCase {
  /**
   * Test the content type
   *
   * Test if the result is JSON string.
   *
   * @since 1.0.0
   */
  public function test_result_type() {

    $parser = new Admin\Parse();

    $result = $parser->parse_content_to_json( '<div id="test">This is a test.</div>' );

    $this->assertTrue( is_string( $result ), 'Parsed content should be a JSON sting.' );
  }

  /**
   * Test the parse_content_to_json method
   *
   * Test if the method parse_content_to_json exists.
   *
   * @since 1.0.0
   */
  public function test_method_type() {

    $parser = new Admin\Parse();

    $this->assertTrue( method_exists( $parser , 'parse_content_to_json' ) );
  }

  /**
   * Test the content
   *
   * Test if the given HTML parses to a correct JSON.
   *
   * @param string $provided_json Provided json to the test method.
   * @param string $provided_html Provided html to the test method.
   * @since 1.0.0
   *
   * @dataProvider provider_test_return_json_content
   */
  public function test_html_content( $provided_json, $provided_html ) {

    $parser = new Admin\Parse();

    $result_json = $parser->parse_content_to_json( $provided_html );

    $this->assertEquals( $provided_json, $result_json );
  }

  /**
   * Data test provider
   *
   * @return array Array of provided data to test.
   * @since 1.0.0
   */
  public function provider_test_return_json_content() {
    return array(
        array( '{"node":"element","tag":"html","child":[{"node":"element","tag":"body","child":[{"node":"element","tag":"div","attr":{"id":"1","class":"foo"},"child":[{"node":"element","tag":"h2","child":[{"node":"text","text":"sample text with "},{"node":"element","tag":"code","child":[{"node":"text","text":"inline tag"}]}]},{"node":"element","tag":"pre","attr":{"id":"demo","class":"foo bar"},"child":[{"node":"text","text":"foo"}]},{"node":"element","tag":"pre","attr":{"id":"output","class":"goo"},"child":[{"node":"text","text":"goo"}]},{"node":"element","tag":"input","attr":{"id":"execute","type":"button","value":"execute"}}]}]}]}', '<div id="1" class="foo"><h2>sample text with <code>inline tag</code></h2><pre id="demo" class="foo bar">foo</pre><pre id="output" class="goo">goo</pre><input id="execute" type="button" value="execute"/></div>' ),
        array( '{"node":"element","tag":"html","child":[{"node":"element","tag":"body","child":[{"node":"element","tag":"div"}]}]}', '<div></div>' ),
    );
  }
}

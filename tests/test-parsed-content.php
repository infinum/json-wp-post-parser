<?php
/**
 * Class Parsed_Content
 *
 * @package Json_Wp_Post_Parser
 */

use jsonWpPostParser\Admin;

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
  public function test_html_content() {

    /**
     *  <div id="1" class="foo">
     *  <h2>sample text with <code>inline tag</code>
     *  </h2>
     *  <pre id="demo" class="foo bar">foo</pre>
     *  <pre id="output" class="goo">goo</pre>
     *  <input id="execute" type="button" value="execute"/>
     *  </div>
     */
    $html = '<div id="1" class="foo"><h2>sample text with <code>inline tag</code></h2><pre id="demo" class="foo bar">foo</pre><pre id="output" class="goo">goo</pre><input id="execute" type="button" value="execute"/></div>';

    $json = '{"node":"element","tag":"html","child":[{"node":"element","tag":"body","child":[{"node":"element","tag":"div","attr":{"id":"1","class":"foo"},"child":[{"node":"element","tag":"h2","child":[{"node":"text","text":"sample text with "},{"node":"element","tag":"code","child":[{"node":"text","text":"inline tag"}]}]},{"node":"element","tag":"pre","attr":{"id":"demo","class":"foo bar"},"child":[{"node":"text","text":"foo"}]},{"node":"element","tag":"pre","attr":{"id":"output","class":"goo"},"child":[{"node":"text","text":"goo"}]},{"node":"element","tag":"p","child":[{"node":"element","tag":"input","attr":{"id":"execute","type":"button","value":"execute"}}]}]}]}]}';

    $parser = new Json_WP_Post_Parser_Parse();

    $result = $parser->parse_content_to_json( $html );

    $this->assertEquals( $json, $result );
  }
}

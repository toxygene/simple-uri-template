<?php
namespace SimpleUriTemplate\Tests;

use PHPUnit_Framework_TestCase;
use SimpleUriTemplate\UriParser;

/**
 * Unit tests for the URI parser
 *
 * @coversDefaultClass \SimpleUriTemplate\UriParser
 */
class UriParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test that the regex is created for a template with no placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithNoPlaceholders()
    {
        $parser = new UriParser('/one/two');
        $this->assertEquals('/one/two', $parser->parse());
    }

    public function testTest()
    {
        $parser = new UriParser('/one/{two}/{three}');
        $this->assertEquals('/one/2/3', $parser->parse(['two' => 2, 'three' => 3]));
    }

}

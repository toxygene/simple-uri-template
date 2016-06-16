<?php
namespace Toxygene\SimpleUriTemplate\Tests;

use PHPUnit_Framework_TestCase;
use Toxygene\SimpleUriTemplate\UriParser;

/**
 * Unit tests for the URI parser
 *
 * @coversDefaultClass \Toxygene\SimpleUriTemplate\UriParser
 */
class UriParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * Regex parser
     *
     * @var UriParser
     */
    private $parser;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        $this->parser = new UriParser();
    }

    /**
     * Test that the regex is created for a template with no placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithNoPlaceholders()
    {
        $this->assertEquals('/one/two', $this->parser->parse('/one/two'));
    }

    public function testTest()
    {
        $this->assertEquals('/one/2/3', $this->parser->parse('/one/{two}/{three}', ['two' => 2, 'three' => 3]));
    }

}

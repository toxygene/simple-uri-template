<?php
namespace SimpleUriTemplate\Tests;

use PHPUnit_Framework_TestCase;
use SimpleUriTemplate\Lexer;
use SimpleUriTemplate\UriParser;

/**
 * Unit tests for the URI parser
 *
 * @coversDefaultClass \SimpleUriTemplate\UriParser
 */
class UriParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * Template language lexer
     *
     * @var Lexer
     */
    private $lexer;

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
        $this->lexer = new Lexer();
        $this->parser = new UriParser($this->lexer);
    }

    /**
     * Test that the regex is created for a template with no placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithNoPlaceholders()
    {
        $this->lexer->setInput('/one/two');
        $this->assertEquals('/one/two', $this->parser->parse());
    }

    public function testTest()
    {
        $this->lexer->setInput('/one/{two}/{three}');
        $this->assertEquals('/one/2/3', $this->parser->parse(['two' => 2, 'three' => 3]));
    }

}

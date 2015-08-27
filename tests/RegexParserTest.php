<?php
namespace SimpleUriTemplate\Tests;

use PHPUnit_Framework_TestCase;
use SimpleUriTemplate\Lexer;
use SimpleUriTemplate\RegexParser;

/**
 * Unit tests for the regex parser
 *
 * @coversDefaultClass \SimpleUriTemplate\RegexParser
 */
class RegexParserTest extends PHPUnit_Framework_TestCase
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
     * @var RegexParser
     */
    private $parser;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        $this->lexer = new Lexer();
        $this->parser = new RegexParser($this->lexer);
    }

    /**
     * Test that the regex is created for a template with no placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithNoPlaceholders()
    {
        $this->lexer->setInput('/one/two');
        $this->assertEquals('#^/one/two$#', $this->parser->parse());
    }

    /**
     * Test that the regex is created for a template with placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithPlaceholders()
    {
        $this->lexer->setInput('/one/{two}/{three}');
        $this->assertEquals('#^/one/(?P<two>.+?)/(?P<three>.+?)$#', $this->parser->parse());
    }

}

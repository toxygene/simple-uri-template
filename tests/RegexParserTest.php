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
        $this->parser = new RegexParser(new Lexer());
    }

    /**
     * Test that the regex is created for a template with no placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithNoPlaceholders()
    {
        $this->assertEquals('#^/one/two$#', $this->parser->parse('/one/two'));
    }

    /**
     * Test that the regex is created for a template with placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithPlaceholders()
    {
        $this->assertEquals('#^/one/(?P<two>.+?)/(?P<three>.+?)$#', $this->parser->parse('/one/{two}/{three}'));
    }

}

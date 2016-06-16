<?php
namespace Toxygene\SimpleUriTemplate\Tests;

use PHPUnit_Framework_TestCase as TestCase;
use Toxygene\SimpleUriTemplate\ParserException;
use Toxygene\SimpleUriTemplate\RegexParser;

/**
 * Unit tests for the regex parser
 *
 * @coversDefaultClass \Toxygene\SimpleUriTemplate\RegexParser
 * @covers ::__construct
 */
class RegexParserTest extends TestCase
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
        $this->parser = new RegexParser();
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
     * @covers ::match
     * @covers ::parse
     * @covers ::parsePlaceholder
     */
    public function testRegexIsCreatedForTemplateWithPlaceholders()
    {
        $this->assertEquals('#^/one/(?P<two>.+?)/(?P<three>/d+?)$#', $this->parser->parse('/one/{two}/{three:/d+}'));
    }

    /**
     * Test that invalid syntax throws a syntax error
     *
     * @covers ::match
     * @covers ::parse
     * @covers ::parsePlaceholder
     * @covers ::syntaxError
     */
    public function testInvalidSyntaxThrowsASyntaxError()
    {
        $this->setExpectedException(ParserException::class);

        $this->parser
            ->parse('{{');
    }

    /**
     * Test that invalid syntax at the end of the string throws a syntax error
     *
     * @covers ::match
     * @covers ::parse
     * @covers ::parsePlaceholder
     * @covers ::syntaxError
     */
    public function testInvalidSyntaxAtTheEndOfTheStringThrowsASyntaxError()
    {
        $this->setExpectedException(ParserException::class);

        $this->parser
            ->parse('test{');
    }

    /**
     * Test that an invalid placeholder end throws a syntax error

     * @covers ::parse
     * @covers ::syntaxError
     */
    public function testInvalidPlaceholderEndThrowsASyntaxError()
    {
        $this->setExpectedException(ParserException::class);

        $this->parser->parse('}');
    }
}

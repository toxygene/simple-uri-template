<?php
namespace SimpleUriTemplate\Tests;

use PHPUnit_Framework_TestCase;
use SimpleUriTemplate\Lexer;

/**
 * Unit tests for the lexer
 *
 * @coversDefaultClass \SimpleUriTemplate\Lexer
 */
class LexerTest extends PHPUnit_Framework_TestCase
{

    /**
     * Template language lexer
     *
     * @var Lexer
     */
    private $lexer;

    /**
     * Setup the test case
     */
    public function setUp()
    {
        $this->lexer = new Lexer();
    }

    /**
     * Tear down the test case
     */
    public function tearDown()
    {
        unset($this->lexer);
    }

    /**
     * Test that a { is tokenized as a placeholder start
     *
     * @covers ::getType
     */
    public function testPlaceholderStartIsTokenized()
    {
        $this->assertTrue($this->lexer->isA('{', Lexer::T_PLACEHOLDER_START));
    }

    /**
     * Test that a } is tokenized as a placeholder stop
     *
     * @covers ::getType
     */
    public function testPlaceholderStopIsTokenized()
    {
        $this->assertTrue($this->lexer->isA('}', Lexer::T_PLACEHOLDER_STOP));
    }

    /**
     * Test that a string is tokenized as a string
     *
     * @covers ::getType
     */
    public function testStringIsTokenized()
    {
        $this->assertTrue($this->lexer->isA('asdf', Lexer::T_STRING));
    }

    /**
     * Test that a identifier is tokenized as an identifier
     *
     * @covers ::getType
     */
    public function testIdentifierIsTokenized()
    {
        $this->lexer->setInPlaceholder(true);
        $this->assertTrue($this->lexer->isA('asdf', Lexer::T_IDENTIFIER));
    }

}
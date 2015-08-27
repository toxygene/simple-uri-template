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

    /**
     * Test lexing a full input tokenizes as expected
     *
     * @covers ::getType
     */
    public function testLexingInputResultsInTheCorrectTokens()
    {
        $this->lexer->setInput('/one/two/{three}/four/{five}');
        $this->lexer->moveNext();

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_STRING, $this->lexer->token['type']);
        $this->assertEquals('/one/two/', $this->lexer->token['value']);

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_PLACEHOLDER_START, $this->lexer->token['type']);
        $this->assertEquals('{', $this->lexer->token['value']);

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_IDENTIFIER, $this->lexer->token['type']);
        $this->assertEquals('three', $this->lexer->token['value']);

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_PLACEHOLDER_STOP, $this->lexer->token['type']);
        $this->assertEquals('}', $this->lexer->token['value']);

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_STRING, $this->lexer->token['type']);
        $this->assertEquals('/four/', $this->lexer->token['value']);

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_PLACEHOLDER_START, $this->lexer->token['type']);
        $this->assertEquals('{', $this->lexer->token['value']);

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_IDENTIFIER, $this->lexer->token['type']);
        $this->assertEquals('five', $this->lexer->token['value']);

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_PLACEHOLDER_STOP, $this->lexer->token['type']);
        $this->assertEquals('}', $this->lexer->token['value']);

        $this->assertNull($this->lexer->lookahead);
    }

    /**
     * Test lexing an input without placeholders tokenizes as expected
     *
     * @covers ::getType
     */
    public function testLexingInputWithoutPlaceholders()
    {
        $this->lexer->setInput('/one');
        $this->lexer->moveNext();

        $this->lexer->moveNext();
        $this->assertToken(Lexer::T_STRING, $this->lexer->token['type']);
        $this->assertEquals('/one', $this->lexer->token['value']);

        $this->assertNull($this->lexer->lookahead);
    }

    /**
     * Assert an expected token
     *
     * @param mixed $expected
     * @param mixed $actual
     */
    private function assertToken($expected, $actual)
    {
        $this->assertEquals(
            $expected,
            $actual,
            sprintf(
                'Expected %s, got %s',
                $this->lexer->getLiteral($expected),
                $this->lexer->getLiteral($actual)
            )
        );
    }

}
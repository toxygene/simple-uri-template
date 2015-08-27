<?php
namespace SimpleUriTemplate\Tests;

use PHPUnit_Framework_TestCase;
use SimpleUriTemplate\RegexParser;

/**
 * Unit tests for the regex parser
 *
 * @coversDefaultClass \SimpleUriTemplate\RegexParser
 */
class RegexParserTest extends PHPUnit_Framework_TestCase
{

    /**
     * Test that the regex is created for a template with no placeholders
     *
     * @covers ::parse
     */
    public function testRegexIsCreatedForTemplateWithNoPlaceholders()
    {
        $parser = new RegexParser('/one/two');
        $this->assertEquals('#^/one/two$#', $parser->parse());
    }

    public function testRegexIsCreatedForTemplateWithPlaceholders()
    {
        $parser = new RegexParser('/one/{two}/{three}');
        $this->assertEquals('#^/one/(?P<two>.+?)/(?P<three>.+?)$#', $parser->parse());
    }

}

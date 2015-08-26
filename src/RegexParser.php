<?php
namespace SimpleUriTemplate;

use \Exception;

/**
 * Parse the template language to a regular expression
 */
class RegexParser
{

    /**
     * Template language lexer
     *
     * @var Lexer
     */
    private $lexer;

    /**
     * Constructor
     *
     * @param string $template
     */
    public function __construct($template)
    {
        $this->lexer = new Lexer();
        $this->lexer->setInput($template);
    }

    /**
     * Parse the template to a regular expression
     *
     * @return string
     */
    public function parse()
    {
        $regex = '';

        while ($this->lexer->moveNext()) {
            switch ($this->lexer->token['type']) {
                case Lexer::T_PLACEHOLDER_START:
                    $regex .= $this->parsePlaceholder();
                    break;

                case Lexer::T_STRING:
                    $regex .= $this->lexer->token['value'];
                    break;
            }
        }

        return "#{$regex}#";
    }

    /**
     * Parse the placeholder identifier
     *
     * @return string
     * @throws Exception
     */
    private function parsePlaceholder()
    {
        $this->assertNextToken(Lexer::T_IDENTIFIER);
        $this->lexer->moveNext();

        $result = '(?P<' . $this->lexer->token['value'] . '>.+?)';

        $this->assertNextToken(Lexer::T_PLACEHOLDER_STOP);
        $this->lexer->moveNext();

        return $result;
    }

    /**
     * Assert the next token is of a specified type
     *
     * @param string $token
     * @throws Exception
     */
    private function assertNextToken($token)
    {
        if (null === $this->lexer->lookahead) {
            throw new Exception(sprintf(
                'At position %s, expected "%s", got end of line',
                $this->lexer->token['position'],
                $this->lexer->getLiteral($token)
            ));
        }

        if ($this->lexer->lookahead['type'] !== $token) {
            throw new Exception(sprintf(
                'At position %s, expected "%s", got "%s"',
                $this->lexer->lookahead['position'],
                $this->lexer->getLiteral($token),
                $this->lexer->getLiteral($this->lexer->lookahead['type'])
            ));
        }
    }

}

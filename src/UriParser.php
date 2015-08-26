<?php
namespace SimpleUriTemplate;

use \Exception;

/**
 * Parse the template language to a URI
 */
class UriParser
{

    private $lexer;

    public function __construct($template)
    {
        $this->lexer = new Lexer();
        $this->lexer->setInput($template);
    }

    /**
     * Parse the template to a URI
     *
     * @param array $parameters
     * @return string
     */
    public function parse($parameters)
    {
        $url = '';

        while ($this->lexer->moveNext()) {
            switch ($this->lexer->token['type']) {
                case Lexer::T_PLACEHOLDER_START:
                    $url .= $this->parsePlaceholder($parameters);
                    break;

                case Lexer::T_STRING:
                    $url .= $this->lexer->token['value'];
                    break;
            }
        }

        return $url;
    }

    /**
     * Parse the placeholder identifier
     *
     * @return string
     * @throws Exception
     */
    private function parsePlaceholder($parameters)
    {
        $this->assertNextToken(Lexer::T_IDENTIFIER);
        $this->lexer->moveNext();

        if (!isset($parameters[$this->lexer->token['value']])) {
            throw new Exception(sprintf(
                'At position %s, identifier "%s" not found in supplied parameters',
                $this->lexer->token['position'],
                $this->lexer->token['value']
            ));
        }

        $result = urlencode($parameters[$this->lexer->token['value']]);

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

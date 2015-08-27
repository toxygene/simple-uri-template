<?php
namespace SimpleUriTemplate;

use \Exception;
use Symfony\Component\Yaml\Exception\ParseException;

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
    public function parse($parameters = [])
    {
        $url = '';

        while ($this->lexer->moveNext()) {
            switch ($this->lexer->lookahead['type']) {
                case Lexer::T_PLACEHOLDER_START:
                    $url .= $this->parsePlaceholder($parameters);
                    break;

                case Lexer::T_STRING:
                    $url .= $this->lexer->lookahead['value'];
                    break;
            }
        }

        return $url;
    }

    /**
     * Parse the placeholder identifier
     *
     * @param array $parameters
     * @return string
     * @throws ParserException
     */
    private function parsePlaceholder($parameters)
    {
        $this->match(Lexer::T_PLACEHOLDER_START)
            ->match(Lexer::T_IDENTIFIER);

        if (!isset($parameters[$this->lexer->token['value']])) {
            throw new ParserException(sprintf(
                'line 0, col %s: Error: %s not found in parameters',
                $this->lexer->token['position'],
                $this->lexer->token['value']
            ));
        }

        $result = urlencode($parameters[$this->lexer->token['value']]);

        $this->match(Lexer::T_PLACEHOLDER_STOP, false);

        return $result;
    }

    /**
     * Assert the next token is of a specified type
     *
     * @param string $token
     * @param boolean $moveNext
     * @return $this
     */
    private function match($token, $moveNext = true)
    {
        $lookaheadType = $this->lexer->lookahead['type'];

        if ($lookaheadType !== $token) {
            $this->syntaxError($this->lexer->getLiteral($token));
        }

        if ($moveNext) {
            $this->lexer->moveNext();
        }

        return $this;
    }

    /**
     * Throw a syntax error
     *
     * @param string $expected
     * @throws ParserException
     */
    private function syntaxError($expected)
    {
        $token = $this->lexer->lookahead;
        $tokenPosition = isset($token['position']) ? $token['position'] : '-1';

        $message = sprintf(
            'line 0, col %s: Error: Expected %s, got ',
            $tokenPosition,
            $expected
        );

        if ($this->lexer->lookahead === null) {
            $message .= 'end of string';
        } else {
            $message .= $this->lexer->getLiteral($token['type']);
        }

        throw new ParserException($message);
    }

}

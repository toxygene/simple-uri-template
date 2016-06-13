<?php
namespace Toxygene\SimpleUriTemplate;

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
     * @param Lexer $lexer
     */
    public function __construct(Lexer $lexer = null)
    {
        if (null === $lexer) {
            $lexer = new Lexer();
        }

        $this->lexer = $lexer;
    }

    /**
     * Parse the template to a regular expression
     *
     * @param string $template
     * @return string
     * @throws ParserException
     */
    public function parse($template)
    {
        $this->lexer
            ->setInput($template);

        $regex = '';

        while ($this->lexer->moveNext()) {
            switch ($this->lexer->lookahead['type']) {
                case Lexer::T_PLACEHOLDER_START:
                    $regex .= $this->parsePlaceholder();
                    break;

                case Lexer::T_STRING:
                    $regex .= $this->lexer->lookahead['value'];
                    break;

                default:
                    $this->syntaxError(sprintf(
                        '%s or %s',
                        $this->lexer->getLiteral(Lexer::T_PLACEHOLDER_START),
                        $this->lexer->getLiteral(Lexer::T_STRING)
                    ));
            }
        }

        return "#^{$regex}$#";
    }

    /**
     * Parse the placeholder identifier
     *
     * @return string
     * @throws ParserException
     */
    private function parsePlaceholder()
    {
        $this->match(Lexer::T_PLACEHOLDER_START)
            ->match(Lexer::T_IDENTIFIER);

        $regex = '(?P<' . $this->lexer->token['value'] . '>.+?)';

        $this->match(Lexer::T_PLACEHOLDER_STOP, false);

        return $regex;
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

<?php
/**
 *
 */

namespace Toxygene\SimpleUriTemplate;

use Nette\Utils\TokenIterator;

/**
 * Parse the template language to a regular expression
 */
class RegexParser
{

    /**
     * Tokenizer
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tokenizer = new Tokenizer();
    }

    /**
     * Parse the input to a regular expression
     *
     * @param string $input
     * @return string
     * @throws ParserException
     */
    public function parse($input)
    {
        $tokenIterator = new TokenIterator($this->tokenizer->tokenize($input));

        $regex = '';
        while ($tokenIterator->nextToken()) {
            if ($tokenIterator->isCurrent(Tokenizer::T_PLACEHOLDER_START)) {
                $regex .= $this->parsePlaceholder($tokenIterator);
            } elseif ($tokenIterator->isCurrent(Tokenizer::T_LITERAL)) {
                $regex .= $tokenIterator->currentValue();
            } else {
                throw new ParserException();
            }
        }

        return "#^{$regex}$#";
    }

    /**
     * Parse the placeholder
     *
     * @param TokenIterator $tokenIterator
     * @return string
     * @throws ParserException
     */
    private function parsePlaceholder(TokenIterator $tokenIterator)
    {
        $tokenIterator->nextToken();
        if (!$tokenIterator->isCurrent(Tokenizer::T_IDENTIFIER)) {
            throw new ParserException();
        }

        $regex = '(?P<' . $tokenIterator->currentValue() . '>';

        $identifierRegex = $this->parseIdentifierRegex($tokenIterator);
        if (!$identifierRegex) {
            $identifierRegex = '.+?';
        }

        $regex .= $identifierRegex;

        if (!$tokenIterator->isNext(Tokenizer::T_PLACEHOLDER_END)) {
            throw new ParserException();
        }

        $tokenIterator->nextToken();

        return $regex . ')';
    }

    /**
     * Parse the optional identifier regex
     *
     * @param TokenIterator $tokenIterator
     * @return NULL|string
     */
    private function parseIdentifierRegex(TokenIterator $tokenIterator)
    {
        if (!$tokenIterator->isNext(Tokenizer::T_SEPARATOR)) {
            return '';
        }

        $tokenIterator->nextToken();

        if (!$tokenIterator->isNext(Tokenizer::T_REGEX)) {
            throw new ParserException();
        }

        $tokenIterator->nextToken();

        $regex = $tokenIterator->currentValue();

        return $regex . '?';
    }

}

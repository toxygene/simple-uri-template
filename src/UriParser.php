<?php
namespace Toxygene\SimpleUriTemplate;

use \Exception;
use Nette\Utils\TokenIterator;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Parse the template language to a URI
 */
class UriParser
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
     * Parse the template to a URI
     *
     * @param string $template
     * @param array $parameters
     * @return string
     */
    public function parse($template, $parameters = [])
    {
        $tokenIterator = new TokenIterator($this->tokenizer->tokenize($template));

        $url = '';

        while ($tokenIterator->nextToken()) {
            if ($tokenIterator->isCurrent(Tokenizer::T_PLACEHOLDER_START)) {
                $url .= $this->parsePlaceholder($tokenIterator, $parameters);
            } elseif ($tokenIterator->isCurrent(Tokenizer::T_LITERAL)) {
                $url .= $tokenIterator->currentValue();
            } else {
                throw new ParserException();
            }
        }

        return $url;
    }

    /**
     * Parse the placeholder identifier
     *
     * @param TokenIterator $tokenIterator
     * @param array $parameters
     * @return string
     * @throws ParserException
     */
    private function parsePlaceholder(TokenIterator $tokenIterator, $parameters)
    {
        $tokenIterator->nextToken();
        if (!$tokenIterator->isCurrent(Tokenizer::T_IDENTIFIER)) {
            throw new \RuntimeException();
        }

        $value = $tokenIterator->currentValue();

        if ($tokenIterator->isNext(Tokenizer::T_SEPARATOR)) {
            $tokenIterator->nextToken();

            if (!$tokenIterator->isNext(Tokenizer::T_REGEX)) {
                throw new ParserException();
            }

            $tokenIterator->nextToken();
        }

        if (!$tokenIterator->isNext(Tokenizer::T_PLACEHOLDER_END)) {
            throw new ParserException();
        }

        $tokenIterator->nextToken();

        return $parameters[$value];
    }

}

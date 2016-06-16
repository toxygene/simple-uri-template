<?php
/**
 *
 */

namespace Toxygene\SimpleUriTemplate;

use Nette\Utils\Tokenizer as BaseTokenizer;

/**
 */
class Tokenizer extends BaseTokenizer
{

    /**#@+
     * Token
     * @var string
     */
    const T_PLACEHOLDER_START = 'placeholder_start';
    const T_PLACEHOLDER_END   = 'placeholder_end';
    const T_IDENTIFIER        = 'identifier';
    const T_SEPARATOR         = 'separator';
    const T_REGEX             = 'regex';
    const T_LITERAL           = 'literal';
    /**#@-*/

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct([
            self::T_PLACEHOLDER_START => '(?<!\\\\){',
            self::T_PLACEHOLDER_END   => '(?<!\\\\)}',
            self::T_IDENTIFIER        => '(?<={)[a-zA-Z](?:[a-zA-Z0-9_-]*[a-zA-Z0-9])?(?=}|:)',
            self::T_SEPARATOR         => ':',
            self::T_REGEX             => '(?<=:)[^}]+(?=(?<!\\\\)})',
            self::T_LITERAL           => '(\\\\{|\\\\}|[^{}])+'
        ]);
    }

}

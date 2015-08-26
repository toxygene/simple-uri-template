<?php
namespace SimpleUriTemplate;

use Doctrine\Common\Lexer\AbstractLexer;

/**
 * SimpleUriTemplate language lexer
 */
class Lexer extends AbstractLexer
{

    /**#@+
     * Language token
     *
     * @var string
     */
    const T_PLACEHOLDER_START = 1;
    const T_PLACEHOLDER_STOP  = 2;
    const T_STRING = 3;
    const T_IDENTIFIER = 4;
    /**#@-*/

    /**
     * Flag indicating the lexer is in a placeholder
     *
     * @var boolean
     */
    private $inPlaceholder = false;

    /**
     * Set the in placeholder flag
     *
     * @param boolean $inPlaceholder
     * @return $this
     */
    public function setInPlaceholder($inPlaceholder)
    {
        $this->inPlaceholder = $inPlaceholder;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCatchablePatterns()
    {
        return [
            '[^{}]+'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getNonCatchablePatterns()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function getType(&$value)
    {
        switch ($value) {
            case '}':
                $this->inPlaceholder = false;
                return self::T_PLACEHOLDER_STOP;

            case '{':
                $this->inPlaceholder = true;
                return self::T_PLACEHOLDER_START;

            default:
                return $this->inPlaceholder ? self::T_IDENTIFIER : self::T_STRING;
        }
    }
}

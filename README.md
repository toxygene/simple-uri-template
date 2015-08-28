# Simple URI Template
A simple URI templating language with parsers to convert the template to a regular expression and to a complete URI.

## Installation

`composer install toxygene/simple-uri-template`

## Language Definition
```
   TEMPLATE ::= ( PLACEHOLDER | LITERAL ) { TEMPLATE } *
PLACEHOLDER ::= "{" IDENTIFIER "}"
 IDENTIFIER ::= [a-zA-Z][a-zA-Z0-9]*
    LITERAL ::= [^{}]+
```

## Regex Examples
```php
use SimpleUriTemplate\Lexer;
use SimpleUriTemplate\RegexParser;

$lexer = new Lexer();
$parser = new RegexParser($lexer);

echo $parser->parse('/one/{two}/three'); // #^/one/(?P<two>.+?)/three$#
```

## URI Examples
```php
use SimpleUriTemplate\Lexer;
use SimpleUriTemplate\UriParser;

$lexer = new Lexer();
$parser = new UriParser($lexer);

echo $parser->parse('/one/{two}/three', ['two' => 2]); // /one/2/three
```

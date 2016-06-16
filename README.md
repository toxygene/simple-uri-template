# Simple URI Template
A simple URI template language with a lexer and parsers to output a regular expression that will match the template or expand the template to a complete string from an array of attributes.

The primary use case is for assembling and matching path-based routes.

## Installation
`composer install toxygene/simple-uri-template`

## Language Definition
```
   TEMPLATE = { EXPANSION | LITERAL }
  EXPANSION = "{" IDENTIFIER [ ":" REGEXP ] "}"
 IDENTIFIER = [a-zA-Z][a-zA-Z0-9_-]*
     REGEXP = [^}]+
    LITERAL = [^{}]+
```

## Regex Examples
```php
use Toxygene\SimpleUriTemplate\RegexParser;

$parser = new RegexParser();

echo $parser->parse('/one/{two:/d+}/three'); // #^/one/(?P<two>\d+?)/three$#
```

## URI Examples
```php
use Toxygene\SimpleUriTemplate\UriParser;

$parser = new UriParser();

echo $parser->parse('/one/{two}/three', ['two' => 2]); // /one/2/three
```

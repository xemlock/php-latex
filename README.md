# php-latex

[![Build status](https://img.shields.io/circleci/build/gh/xemlock/php-latex?logo=circleci)](https://circleci.com/gh/xemlock/php-latex)
[![License](https://img.shields.io/packagist/l/xemlock/php-latex.svg)](https://packagist.org/packages/xemlock/php-latex)


The main purpose of this library is to provide a valid LaTeX output from, not always valid, user input. You can also render LaTeX code to HTML, with one limitation though - rendering to HTML is done only for the text mode, the math mode needs to be handled by a JavaScript
library - in the browser. For this I recommend using [MathJax](https://www.mathjax.org/).

## Installation

To use php-latex, you install it just as any other php package - with [Composer](https://getcomposer.org/).

```
composer require xemlock/php-latex:dev-master
```

## Usage

Basic usage is as follows:

Parsing LaTex source code:

```php
$parser = new PhpLatex_Parser();
$parsedTree = $this->parse($input);
// $parsedTree contains object representation of the LaTeX document
```

Once you have a parsed source code, you can render it to HTML (or to LaTeX) - please mind that math-mode code is rendered as-is.

```php
// render parsed LaTeX code to HTML
$htmlRenderer = new PhpLatex_Renderer_Html();
$html = $htmlRenderer->render($parsedTree);

// render parsed LaTeX code to sanitized LaTeX code
$latex = PhpLatex_Renderer_Abstract::toLatex($parsedTree);
```

You can also add custom (or not yet implemented) commands to the parser:

```php
$parser = new PhpLatex_Parser();
$parser->addCommand(
    '\placeholder',
    array(
        // number of arguments
        'numArgs' => 1,
        // number of optional arguments, default 0
        'numOptArgs' => 1,
        // mode this command is valid in, can be: 'both', 'math', 'text'
        'mode' => 'both',
        // whether command arguments should be parsed, or handled as-is
        'parseArgs' => false,
    )
);
```

## License

The MIT License (MIT). See the LICENSE file.

# php-latex

[![Build status](https://github.com/xemlock/php-latex/workflows/build/badge.svg)](https://github.com/xemlock/php-latex/actions?query=workflow/build)
[![License](https://img.shields.io/github/license/xemlock/php-latex.svg)](https://packagist.org/packages/xemlock/php-latex)


The main purpose of this library is to provide a valid LaTeX output from, not always valid, user input. You can also render LaTeX code to HTML, with one limitation though - rendering to HTML is done only for the text mode, the math mode needs to be handled by a JavaScript
library - in the browser. For this I recommend using [MathJax](https://www.mathjax.org/).

Bear in mind that not every LaTeX command is recognized or implemented. If you happen to need a command that's
not supported you can either define it manually (see description below), or file a [feature request](https://github.com/xemlock/php-latex/issues/new/choose).

## Installation

To use php-latex, you install it just as any other php package - with [Composer](https://getcomposer.org/).

```
composer require xemlock/php-latex:dev-master
```

## Usage

Basic usage is as follows:

### Parsing LaTeX source code

```php
$parser = new PhpLatex_Parser();
$parsedTree = $parser->parse($input);
// $parsedTree contains object representation of the LaTeX document
```

### Render parsed LaTeX source

Once you have a parsed source code, you can render it to HTML (or to LaTeX) - please mind that math-mode code is rendered as-is.

```php
// render parsed LaTeX code to HTML
$htmlRenderer = new PhpLatex_Renderer_Html();
$html = $htmlRenderer->render($parsedTree);

// render parsed LaTeX code to sanitized LaTeX code
$latex = PhpLatex_Renderer_Abstract::toLatex($parsedTree);
```

### Customization

You can add custom (or not yet implemented) commands to the parser:

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
        // whether command allows a starred variant
        'starred' => false,
    )
);
```

### pdflatex

Additionally, this library provides a wrapper for pdflatex to make rendering and compiling `.tex` files
from PHP scripts easier.

```php
$pdflatex = new PhpLatex_PdfLatex();

// to generate a PDF from .tex file
$pathToGeneratedPdf = $pdflatex->compile('/path/to/document.tex', 
    array(/* optional paths to files included by .tex file (images) */])
);
```

You can access the build log of the last `compile` call via:

```php
echo $pdflatex->getLog();
```

You can even compile on the fly a LaTeX string:

```php
$pathToGeneratedPdf = $pdflatex->compileString('
\documentclass{article}
\begin{document}
Hello from \LaTeX!
\end{document}
');
```

By default, a system temp dir is used for generating PDF from string. You can however customize it:

```php
$pdflatex->setBuildDir('/path/to/temp'); 
```

## License

The MIT License (MIT). See the LICENSE file.

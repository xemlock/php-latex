<?php

return array(
    'verbatim'    => array(
        'verbatim'  => true,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate'),
        'starred'   => true,
        // verbatim in tabular causes
        // ! LaTeX Error: Something's wrong--perhaps a missing \item.
    ),
    'Verbatim'    => array(
        'verbatim'  => true,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate'),
    ),
    'lstlisting'  => array(
        'verbatim'  => true,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate'),
    ),
    'enumerate'   => array(
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate'),
    ),
    'itemize'     => array(
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate'),
        // itemize in tabular causes
        // ! LaTeX Error: Something's wrong--perhaps a missing \item.
    ),
    'displaymath' => array(
        'math'      => true,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate'),
        // displaymath in tabular causes
        // ! LaTeX Error: Bad math environment delimiter.
    ),
    'math'        => array(
        'math'      => true,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate', 'tabular'),
    ),
    'equation' => array(
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'math'      => true,
        'starred'   => true,
    ),
    'eqnarray' => array(
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'math'      => true,
        'starred'   => true,
    ),
    'tabular' => array(
        'numArgs'   => 1,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'environs'  => array('itemize', 'enumerate', 'tabular'),
    ),
    'array' => array(
        'numArgs'   => 1,
        'mode'      => PhpLatex_Parser::MODE_MATH,
    ),
);

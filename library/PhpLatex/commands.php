<?php
// environs - required environments
return array(
    '\\par' => array(
        'numArgs'   => 0,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
    ),
    '\\string' => array( // TeX primitive
        'numArgs'   => 1,
        'parseArgs' => false,
        'mode'      => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\newline' => array(
        'numArgs'   => 0,
        'mode'      => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\\\' => array(
        'numArgs'   => 0,
        'mode'      => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\TeX' => array(
        'numArgs'   => 0,
        'mode'      => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\LaTeX' => array(
        'numArgs'   => 0,
        'mode'      => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\chapter' => array(
        'numArgs'   => 1,
        'mode'      => PhpLatex_Parser::MODE_TEXT,
        'starred'   => true,
        'counter'   => 'chapter',
        'counterReset' => array(
            'section', 'subsection', 'subsubsection', 'paragraph', 'subparagraph',
        ),
    ),
    '\\section' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
        'starred'      => true,
        'counter'      => 'section',
        'counterReset' => array(
            'subsection', 'subsubsection', 'paragraph', 'subparagraph',
        ),
    ),
    '\\subsection' => array(
        'numArgs'     => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
        'starred'      => true,
        'counter'      => 'subsection',
        'counterReset' => array(
            'subsubsection', 'paragraph', 'subparagraph',
        ),
    ),
    '\\subsubsection' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
        'starred'      => true,
        'counter'      => 'subsubsection',
        'counterReset' => array(
            'paragraph', 'subparagraph',
        ),
    ),
    '\\paragraph' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
        'starred'      => true,
        'counter'      => 'paragraph',
        'counterReset' => array(
            'subparagraph',
        ),
    ),
    '\\subparagraph' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
        'starred'      => true,
        'counter'      => 'subparagraph',
    ),
    '\\textbf' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\textit' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\textrm' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\texttt' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\textsf' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\textup' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\emph' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\textsubscript' => array( // \usepackage{fixltx2e}
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\textsuperscript' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\url' => array( // \usepackage{hyperref}
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
    ),
    '\\href' => array( // \usepackage{hyperref}
        'numArgs'      => 2,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
    ),
    '\\label' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\ref' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_BOTH,
    ),
    '\\item' => array(
        'mode'         => PhpLatex_Parser::MODE_TEXT,
        'environs'     => array('itemize', 'enumerate'),
    ),
    '\\^' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
    ),
    '\\~' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_TEXT,
    ),
    '\\hat' => array(
        'numArgs'      => 1,
        'mode'         => PhpLatex_Parser::MODE_MATH,
    ),
    '\\sim' => array(
        'mode'         => PhpLatex_Parser::MODE_MATH,
    ),
    '\\frac' => array(
        'numArgs'      => 2,
        'mode'         => PhpLatex_Parser::MODE_MATH,
    ),
    '\\sqrt' => array(
        'numArgs'      => 1,
        'numOptArgs'   => 1,
        'mode'         => PhpLatex_Parser::MODE_MATH,
    ),
);

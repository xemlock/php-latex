<?php
// environs - required environments
return array(
    '\\string' => array( // TeX primitive
        'numArgs'   => 1,
        'parseArgs' => false,
        'mode'      => 'both',
    ),
    '\\ ' => array(
        'mode'       => 'both',
        'numArgs'    => 0,
        'numOptArgs' => 0,
    ),
    '\\chapter' => array(
        'numArgs'   => 1,
        'mode'      => 'text',
        'starred'   => true,
        'counter'   => 'chapter',
        'counterReset' => array(
            'section', 'subsection', 'subsubsection', 'paragraph', 'subparagraph',
        ),
    ),
    '\\section' => array(
        'numArgs'      => 1,
        'mode'         => 'text',
        'starred'      => true,
        'counter'      => 'section',
        'counterReset' => array(
            'subsection', 'subsubsection', 'paragraph', 'subparagraph',
        ),
    ),
    '\\subsection' => array(
        'numArgs'     => 1,
        'mode'         => 'text',
        'starred'      => true,
        'counter'      => 'subsection',
        'counterReset' => array(
            'subsubsection', 'paragraph', 'subparagraph',
        ),
    ),
    '\\subsubsection' => array(
        'numArgs'      => 1,
        'mode'         => 'text',
        'starred'      => true,
        'counter'      => 'subsubsection',
        'counterReset' => array(
            'paragraph', 'subparagraph',
        ),
    ),
    '\\paragraph' => array(
        'numArgs'      => 1,
        'mode'         => 'text',
        'starred'      => true,
        'counter'      => 'paragraph',
        'counterReset' => array(
            'subparagraph',
        ),
    ),
    '\\subparagraph' => array(
        'numArgs'      => 1,
        'mode'         => 'text',
        'starred'      => true,
        'counter'      => 'subparagraph',
    ),
    '\\item' => array(
        'mode'         => 'text',
        'environs'     => array('itemize', 'enumerate'),
    ),
    '\\hline' => array(
        'mode'         => 'text',
        'environs'     => array('tabular'),
    ),
);

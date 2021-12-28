<?php

class PhpLatex_Renderer_Html extends PhpLatex_Renderer_Abstract
{
    const FLAG_IGNORE_PAR = 1;
    const FLAG_PAR2BR     = 4;
    const FLAG_ARG        = 2;
    const FLAG_ITEM       = self::FLAG_PAR2BR;

    protected $_commands = array(



    );

    protected $_par = array();

    /**
     * @var PhpLatex_Renderer_Typestyle
     */
    protected $_typestyle;

    /**
     * @var PhpLatex_Parser
     */
    protected $_parser;

    /**
     * @param PhpLatex_Parser $parser
     * @return PhpLatex_Renderer_Html
     */
    public function setParser(PhpLatex_Parser $parser)
    {
        $this->_parser = $parser;
        return $this;
    }

    /**
     * @return PhpLatex_Parser
     */
    public function getParser()
    {
        if ($this->_parser === null) {
            $this->_parser = new PhpLatex_Parser();
        }
        return $this->_parser;
    }

    protected function _renderItem($node, PhpLatex_Utils_PeekableIterator $it) // {{{
    {
        $html = '';

        if ($node->value === '\\item') {
            $it->next(); // skip \item control
        } else {
            return; // skip because no \item control was found
        }

        // stop rendering at first \item control word
        while (($n = $it->current()) && ($n->getType() !== PhpLatex_Parser::TYPE_COMMAND || $n->value !== '\\item')) {
            // consecutive \par macros inside \item are interpreted
            // as a single \newline
            $html .= $this->_renderNode($n, self::FLAG_PAR2BR);
            $next = $it->peek();
            if ($next && ($next->getType() !== PhpLatex_Parser::TYPE_COMMAND || $next->value !== '\\item')) {
                $it->next();
            } else {
                break;
            }
        }

        // in \item all par are converted to newline

        // \newline (and \\) right after \item causes "There's no line here to
        // end" error, newlines after item content are ignored
        $html = preg_replace('/^(\s|<(br|par)\/>)+|(\s|<(br|par)\/>)+$/', '', $html);
        $html = '<li>' . $html . '</li>' . "\n";

        return $html;
    } // }}}

    protected function __renderText($text)
    {
        return str_replace(
            array(
                "\n",
                '---', '--',
                ',,', '``',
                '\'\'', '"',
                '`', '\'',
                '<<', '>>',
                '<', '>',
            ),
            array(
                ' ',
                '&mdash;', '&ndash;',
                '&bdquo;', '&ldquo;',
                '&rdquo;', '&rdquo;',
                '&lsquo;', '&rsquo;',
                '&laquo;', '&raquo;',
                '&lt;' ,'&gt;',
            ),
            $text
        );
    }

    protected function _renderText($node, $flags = 0)
    {
        return $this->__renderText($node->value);
    }

    protected function _renderGroup($node, $flags = 0)
    {
        if (!is_object($node)) {
            throw new Exception;
        }
        // TODO context
        if ($node->mode & PhpLatex_Parser::MODE_MATH) {
            if ($node->optional) {
                // optional argument, for proper nesting must be wrapped in
                // curly braces
                $html = '[{';
            } else {
                $html = '{';
            }
        } else {
            $html = '';
        }

        $tit = new PhpLatex_Utils_PeekableArrayIterator($node->getChildren());
        while ($tit->valid()) {
            $subnode = $tit->current();
            $html .= $this->_renderNode($subnode, $flags);
            $tit->next();
        }

        if ($node->mode & PhpLatex_Parser::MODE_MATH) {
            if ($node->optional) {
                $html .= '}]';
            } else {
                $html .= '}';
            }
        }
        return $html;
    }

    protected function _renderMath($node, $flags = 0)
    {
        if ($node->inline) {
            $delims = array('\\(', '\\)');
        } else {
            $delims = array('\\[', '\\]');
        }

        $html = $this->_renderGroup($node, $flags);

                    // check for forbidden control words
                    /*if (in_array($token['value'], array(
                        '\\def',
                        '\\newcommand', '\\renewcommand',
                        '\\newenvironment', '\\renewenvironment',
                        '\\newfont', '\\newtheorem', '\\usepackage',
                        // MathTex extensions
                        '\\eval', '\\environment', '\\gif',
                    ), true)) {
                        break;
                    }*/

        // filter out certain commands
        // escape unescaped \(, \), \[ and \] in subtree
        // render contents
        // trim
        return $delims[0] . $html . $delims[1];
    }

    // TODO need to know whether special is in math or text mode
    protected function _renderSpecial($node, $flags = 0)
    {
        if ($node->mode & PhpLatex_Parser::MODE_MATH) {
            if ($node->value === '_' || $node->value === '^') {
                $children = $node->getChildren();
                if (count($children)) {
                    return $node->value . $this->_renderNode($children[0], self::FLAG_ARG);
                }
            }
            return $node->value;
        }
        switch ($node->value) {
                case '~':
                    return '&nbsp;';

                case '_':
                case '^':
                    $children = $node->getChildren();
                    if (count($children)) {
                        $tag = $node->value === '_' ? 'sub' : 'sup';
                        $text = $this->_renderNode($children[0], self::FLAG_ARG);
                        return '<' . $tag . '>' . $text . '</' . $tag . '>';
                    }
                    break;
            }
    }

    protected function _renderEnvironList($node)
    {
        $html = '';
        $tag = $node->value === 'itemize' ? 'ul' : 'ol';
        if ($node->getChildren()) {
            // list environments do not inherit flags
            $html .= '<' . $tag . '>';
            $iit = new PhpLatex_Utils_PeekableArrayIterator($node->getChildren());
            while ($iit->valid()) {
                $subnode = $iit->current();
                $html .= $this->_renderItem($subnode, $iit, 0);
                $iit->next();
            }
            $html .= '</' . $tag . '>';
        }
        return $html;
    }

    protected function _renderEnvironTabular($node)
    {
        $children = $node->getChildren();
        $alignment = $this->_renderNodeChildren($children[0]);
        $alignment = preg_replace('/[^crl]/', '', strtolower($alignment));
        // alignment is treated merly as a hint

        $nrows = 0;
        $ncols = 0;
        $row = 0;
        $col = 0;
        $table = array();

        // ltrim spaces
        for ($i = 1; $i < count($children); ++$i) {
            $child = $children[$i];
            if ($child->getType() === PhpLatex_Parser::TYPE_COMMAND &&
                $child->value === '\\\\'
            ) {
                // start new row
                ++$row;
                $col = 0;
                continue;
            }
            if ($child->getType() === PhpLatex_Parser::TYPE_SPECIAL &&
                $child->value === '&'
            ) {
                // start new column
                ++$col;
                continue;
            }

            $cell = $this->_renderNode($child);

            // if last row consists only of an empty string ignore it
            if ($i === count($children) - 1 && $cell === '') {
                break;
            }

            $nrows = max($nrows, $row + 1);
            $ncols = max($ncols, $col + 1);

            if (!isset($table[$row][$col])) {
                $table[$row][$col] = '';
            }

            $table[$row][$col] .= $cell;
        }

        $html = '<table class="table">';
        for ($row = 0; $row < $nrows; ++$row) {
            $html .= '<tr>';
            for ($col = 0; $col < $ncols; ++$col) {
                $align = substr($alignment, $col, 1);
                $style = '';
                if ($align === 'c') {
                    $style = ' style="text-align:center"';
                } elseif ($align === 'l') {
                    $style = ' style="text-align:left"';
                } elseif ($align === 'r') {
                    $style = ' style="text-align:right"';
                }

                $cell = isset($table[$row][$col]) ? trim($table[$row][$col]) : '';
                $html .= '<td' . $style . '>' . $cell . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    protected function _renderEnvironEquation($node)
    {
        $name = 'equation' . ($node->starred ? '*' : '');
        return "\\[\n"
            . "\\begin{{$name}} "
            . $this->_renderNodeChildren($node)
            . " \\end{{$name}}\n"
            . "\\]\n";
    }

    protected function _renderEnvironEqnarray($node)
    {
        $name = 'eqnarray' . ($node->starred ? '*' : '');
        return "\\[ \\begin{{$name}}\n"
            . $this->_renderNodeChildren($node)
            . " \end{{$name}} \\]\n";
    }

    protected function _renderEnvironMath($node)
    {
        return "\\( " . $this->_renderNodeChildren($node) . " \\) ";
    }

    protected function _renderEnvironDisplaymath($node)
    {
        return "\\[\n" . $this->_renderNodeChildren($node) . " \\]\n";
    }

    protected function _renderEnvironVerbatim($node)
    {
        $child = $node->getChild(0);
        return '<pre class="latex-verbatim">' . htmlspecialchars($child->value)  . '</pre>';
    }

    protected function _renderNodeChildren($node)
    {
        $html = '';
        foreach ($node->getChildren() as $child) {
            $html .= $this->_renderNode($child, 0);
        }
        return $html;
    }

    // assumption $it->current() === $node
    // increment iterator only if next node is required for rendering of
    // this node
    protected function _renderNode($node, $flags = 0)
    {
        if ($node->getType() === PhpLatex_Parser::TYPE_ENVIRON) {
            $html = '';
            switch ($node->value) {
                case 'itemize':
                case 'enumerate':
                    return $this->_renderEnvironList($node);

                default:
                    $method = '_renderEnviron' . $node->value;
                    if (method_exists($this, $method)) {
                        return $this->$method($node);
                    }
                    // invalid environment, render its contents
                    $html = $this->_renderNodeChildren($node);
                    break;
            }
            return $html;
        }

        if ($node->getType() === PhpLatex_Parser::TYPE_VERBATIM) {
            return $this->__renderText($node->value);
        }

        if ($node->getType() === PhpLatex_Parser::TYPE_COMMAND) {
            // TODO filter out forbidden control sequences
            if ($node->mode & PhpLatex_Parser::MODE_MATH) {
                $html = $this->_renderNodeChildren($node);
                // don't append space if control symbol

                return $node->value . ($html ? $html : ($node->symbol ? '' : ' '));
            }
            if ($this->hasCommandRenderer($node->value)) {
                return $this->executeCommandRenderer($node->value, $node);
            }
            switch ($node->value) {
                case '\\S':
                    return '&sect;';

                case '\\P':
                    return '&para;';

                case '\\ldots':
                case '\\dots':
                    return '&hellip;';

                case '\\textbackslash':
                    return '\\';

                case '\\textasciitilde':
                    return '~';

                case '\\textasciicircum':
                    return '^';

                case '\\-':
                    return ''; // word hyphenation

                case '\\^':
                case '\\~':
                    if ($arg = $node->getChild(0)) {
                        $arg = trim($this->_renderNodeChildren($arg));
                    }
                    if (0 === strlen($arg)) {
                        return substr($node->value, 1);
                    }
                    return $arg; // TODO support for circumflex/tilde accent

                case '\\#':
                case '\\%':
                case '\\_':
                case '\\{':
                case '\\}':
                case '\\$':
                    return substr($node->value, 1);

                // spaces, based on https://en.wikipedia.org/wiki/Whitespace_character#Unicode
                case '\\ ':
                    return '&nbsp;';
                case '\\,':
                    return '&thinsp;';
                case '\\enspace':
                    return '&ensp;';
                case '\\quad':
                    return '&emsp;';

                case '\\ref':
                    // TODO if ref target resides in math mode render \\ref, so that
                    // it can be handled by JS.
                    return "\\ref{" . trim($this->_renderNodeChildren($node), "{}") . '} ';

                case '\\&':
                    return '&amp;';

                case '\\\\':
                case '\\newline';
                    return '<br/>';

                case '\\par':
                    // replace \par in argument with space
                    if ($flags & self::FLAG_ARG) {
                        return ' '; // ok
                    }

                    // par placeholder for further processing (par will be
                    // inserted or removed if certain conditions are met)
                    return '<par/>';

                case '\\url':
                case '\\href':
                    $args = $node->getChildren();
                    if (count($args) > 0) {
                        // term arg (not text) causes the following error:
                        // ! TeX capacity exceeded, sorry [input stack size=5000].

                        // TODO validate url, only (ht|f)tp(s)?:// urls
                        $url = $this->_renderNode($args[0]);
                        $urlAttr = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $url);

                        $text = count($args) > 1 ? $this->_renderNode($args[1]) : $url;

                        return "<a href=\"" . $urlAttr . "\">" . $text . "</a>";
                    }
                    break;

                case '\\TeX':
                    return '<span style="font-size:1em;text-transform:uppercase;font-family:serif">T<sub style="line-height:1;font-size:1em;vertical-align:-0.5ex;margin-left:-0.1667em;margin-right:-0.125em;top:0;bottom:0">e</sub>X</span>';

                case '\\LaTeX':
                    return '<span style="font-size:1em;text-transform:uppercase;font-family:serif">L<sup style="line-height:1;font-size:0.85em;vertical-align:0.15em;margin-left:-0.36em;margin-right:-0.15em;top:0;bottom:0">A</sup>T<sub style="line-height:1;font-size:1em;vertical-align:-0.5ex;margin-left:-0.1667em;margin-right:-0.125em;top:0;bottom:0">e</sub>X</span>';

                case '\\chapter':
                case '\\section':
                case '\\subsection':
                case '\\subsubsection':
                case '\\paragraph':
                case '\\subparagraph':
                case '\\textsubscript': // \usepackage{fixltx2e}
                case '\\textsuperscript':
                    foreach ($node->getChildren() as $arg) {
                        switch ($node->value) {
                            case '\\chapter':
                                $tag = 'h1';
                                break;

                            case '\\section':
                                $tag = 'h2';
                                break;

                            case '\\subsection':
                                $tag = 'h3';
                                break;

                            case '\\subsubsection':
                                $tag = 'h4';
                                break;

                            case '\\paragraph':
                                $tag = 'h5';
                                break;

                            case '\\subparagraph':
                                $tag = 'h6';
                                break;

                            case '\\textsubscript':
                                $tag = 'sub';
                                break;

                            case '\\textsuperscript':
                                $tag = 'sup';
                                break;
                        }
                        $text = $this->_renderNode($arg, self::FLAG_ARG);
                        $html = '<' . $tag . '>' . $text . '</' . $tag . '>';
                        return $html;
                    }
                    break;

                default:
                    return $this->_renderStyled($node);
                    break;
            }
        }

        $method = '_render' . $node->getType();
        if (method_exists($this, $method)) {
            return $this->$method($node, $flags);
        }
    }

    protected $_initialTypestyle;

    protected function _pushTypestyle()
    {
        if (!$this->_initialTypestyle) {
            $this->_initialTypestyle = new PhpLatex_Renderer_Typestyle();
        }
        if (!$this->_typestyle) {
            $this->_typestyle = $this->_initialTypestyle->push();
        } else {
            $this->_typestyle = $this->_typestyle->push();
        }
        return $this->_typestyle;
    }

    protected function _renderStyled(PhpLatex_Node $node)
    {
        $typestyle = null;

        if ($node->getType() === PhpLatex_Parser::TYPE_COMMAND) {
            switch ($node->value) {
                case '\\textbf':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->bold = true;
                    break;

                case '\\textup':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->style = PhpLatex_Renderer_Typestyle::STYLE_NORMAL;
                    break;

                case '\\textit':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->style = PhpLatex_Renderer_Typestyle::STYLE_ITALIC;
                    break;

                case '\\textsl': // slanted (oblique)
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->style = PhpLatex_Renderer_Typestyle::STYLE_SLANTED;
                    break;

                case '\\emph':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->emphasis = true;
                    break;

                case '\\textrm':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->family = PhpLatex_Renderer_Typestyle::FAMILY_SERIF;
                    break;

                case '\\texttt':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->family = PhpLatex_Renderer_Typestyle::FAMILY_MONO;
                    break;

                case '\\textsf':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->family = PhpLatex_Renderer_Typestyle::FAMILY_SANS;
                    break;

                case '\\underline':
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->underline = true;
                    break;

                case '\\textsc': // small caps
                    $typestyle = $this->_pushTypestyle();
                    $typestyle->smallcaps = true;
                    break;
            }
        }

        $render = null;

        foreach ($node->getChildren() as $arg) {
            $render .= $this->_renderNode($arg, self::FLAG_ARG);
        }

        // wrap in style difference wrt to parent typestyle
        if ($typestyle) {
            if (strlen($render)) {
                $diff = $typestyle->diff();
                if ($diff) {
                    $render = $this->_wrapStyle($render, $diff);
                }
            }
            $this->_typestyle = $typestyle->pop();
        }

        return (string) $render;
    }

    protected function _wrapStyle($render, array $diff = null)
    {
        $tags = array();
        $style = array();

        if (isset($diff['family'])) {
            switch ($diff['family']) {
                case PhpLatex_Renderer_Typestyle::FAMILY_SANS:
                    $style['font-family'] = 'sans-serif';
                    break;

                case PhpLatex_Renderer_Typestyle::FAMILY_MONO:
                    $style['font-family'] = 'monospace';
                    break;

                case PhpLatex_Renderer_Typestyle::FAMILY_SERIF:
                    $style['font-family'] = 'serif';
                    break;
            }
        }

        if (isset($diff['style'])) {
            switch ($diff['style']) {
                case PhpLatex_Renderer_Typestyle::STYLE_NORMAL:
                    $style['font-style'] = 'normal';
                    break;

                case PhpLatex_Renderer_Typestyle::STYLE_SLANTED:
                    $style['font-style'] = 'oblique';
                    break;

                case PhpLatex_Renderer_Typestyle::STYLE_ITALIC:
                    $tags[] = 'i';
                    break;
            }
        }

        if (isset($diff['bold'])) {
            if ($diff['bold']) {
                $tags[] = 'b';
            } else {
                $style['font-weight'] = 'normal';
            }
        }

        if (isset($diff['emphasis'])) {
            if ($diff['emphasis']) {
                $tags[] = 'em';
            }
        }

        if (isset($diff['underline'])) {
            if ($diff['underline']) {
                $tags[] = 'u';
            } else {
                $style['text-decoration'] = 'none';
            }
        }

        if (isset($diff['smallcaps'])) {
            if ($diff['smallcaps']) {
                $style['font-variant'] = 'small-caps';
            } else {
                $style['font-variant'] = 'normal';
            }
        }

        if (!$tags && !$style) {
            return $render;
        }

        if ($tags) {
            $open = $close = '';
            foreach ($tags as $tag) {
                $open .= '<' . $tag . '>';
                $close = $close . '</' . $tag . '>';
            }
            return $open . $render . $close;
        }

        $css = array();
        foreach ($style as $key => $value) {
            $css[] = $key . ':' . $value;
        }
        return sprintf('<span style="%s">%s</span>', implode(';', $css), $render);
    }

    /**
     * @param PhpLatex_Node|string $document
     * @return mixed|string
     */
    public function render($document)
    {
        if (!$document instanceof PhpLatex_Node) {
            $document = $this->getParser()->parse($document);
        }

        $this->_par = array();
        $result = '';

        foreach ($document->getChildren() as $node) {
            $result .= $this->_renderNode($node);
        }

        // fix paragraphs before and after block-level elements

        // skip all \newlines and \\ that came after \par (this may be required
        // when rendering LaTeX output, to avoid 'There's no line here to end'
        // error) and merge multiple \par into one
        $result = preg_replace(
            '/<par\/>(<(br|par)\/>)+/',
            '<par/>',
            $result
        );

        $result = preg_replace('/<par\/><(h1|h2|h3|h4|h5|h6|pre|ul|ol)/i', '<\1', $result);
        $result = preg_replace('/<\/(h1|h2|h3|h4|h5|h6|pre|ul|ol)>(<par\/>)/i', '</\1>', $result);

        // replace par placeholders with their HTML counterparts
        // TODO Maybe P instead of BR?
        $result = str_replace('<par/>', '<br/><br/>', $result);

        return $result;
    }
}

<?php

// Paragraph container. Only non-empty paragraphs are stored,
// a paragraph cannot contain LF characters,
// line break or paragraph start must be explicitly given
class PhpLatex_Filter_ParagraphList implements Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $_paragraphs = array(); // non-empty paragraphs

    /**
     * @var int
     */
    protected $_pos = 0;

    /**
     * @var bool
     */
    protected $_nl = false;

    public function addText($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        if (strlen($text)) {
            // echo '[' . @$this->_paragraphs[$this->_pos] . '](', $text, ') -> ';

            if (isset($this->_paragraphs[$this->_pos])) {
                if ($this->_nl) {
                    if ($text !== ' ') {
                        $this->_nl = false;
                        $par = $this->_paragraphs[$this->_pos] . "\\\\\n" . $text;
                    } else {
                        // do nothing - do not append space-only string or line break
                        // wait for more text to come
                        $par = $text;
                    }
                } else {
                    // append new text to existing paragraph, merge spaces on the
                    // strings boundary into a single space
                    $par = $this->_paragraphs[$this->_pos] . $text;
                    $par = str_replace('  ', ' ', $par);
                }
            } else {
                // new paragraph must start with a non-space character,
                // no line break at the beginning of the paragraph, trailing
                // spaces are allowed (there will be no more than 2)
                $par = $text;
            }

            if (strlen($par)) {
                $this->_paragraphs[$this->_pos] = $par;
            }

            // echo '[' . @$this->_paragraphs[$this->_pos] . ']', "\n\n";
        }


        return $this;
    }

    public function breakLine()
    {
        if ($this->_nl) {
            $this->newParagraph();
        } elseif (isset($this->_paragraphs[$this->_pos]) && !ctype_space($this->_paragraphs[$this->_pos])) {
            // line break can only be placed in a non-empty paragraph
            $this->_nl = true;
        }
        return $this;
    }

    public function newParagraph()
    {
        $this->_nl = false;
        if (isset($this->_paragraphs[$this->_pos])) {
            ++$this->_pos;
        }
        return $this;
    }

    public function clear()
    {
        $this->_paragraphs = array();
        $this->_pos = 0;
        $this->_nl = false;
        return $this;
    }

    public function count()
    {
        return count($this->_paragraphs);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_paragraphs);
    }

    public function __toString()
    {
        if (count($this->_paragraphs)) {
            return preg_replace('/[ ]+/', ' ', implode("\n\n", $this->_paragraphs)) . "\n\n";
        }
        return '';
    }

    public function toArray()
    {
        return $this->_paragraphs;
    }
}

class PhpLatex_Filter_Html2Latex
{
    protected static $_outputEncoding = 'ANSI';

    /**
     * Set output encoding
     * @param $encoding
     */
    public static function setOutputEncoding($encoding)
    {
        self::$_outputEncoding = strtoupper($encoding);
    }

    /**
     * @param  string $html
     * @param  array $options OPTIONAL
     * @return string
     */
    public static function filter($html, array $options = null)
    {
        $errors = libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html);

        libxml_clear_errors();
        libxml_use_internal_errors($errors);

        foreach ($doc->childNodes as $item) {
            if ($item->nodeType == XML_PI_NODE) {
                $doc->removeChild($item);
            }
        }

        $doc->encoding = 'UTF-8';

        $body = $doc->getElementsByTagName('body')->item(0);

        $debug = 0;
        if($debug){
            header('Content-Type: text/plain; charset=utf-8');
            echo $doc->saveHTML(), "\n\n";
        }

        if ($body) {
            $elems = array($body);
            $refs = array();
            $filter = new Zefram_Filter_Slug(); // FIXME dependency!
            // extract all referenced ids of elements, they will be used for internal links creation
            while ($elem = array_shift($elems)) {
                foreach ($elem->childNodes as $item) {
                    if ($item->nodeType === XML_ELEMENT_NODE) {
                        $elems[] = $item;
                    }
                }
                if ($elem->nodeType === XML_ELEMENT_NODE && strtoupper($elem->tagName) === 'A') {
                    $href = trim($elem->getAttribute('href'));
                    if (strlen($href) && $href{0} === '#') {
                        $id = substr($href, 1);
                        $refs[$id] = 'ref:' . $filter->filter(str_ireplace('ref:', '', $id));
                    }
                }
            }

            self::$_refs = $refs;

            // TODO create IDs map
            $latex = self::processBlock($body, self::TRIM);

            if($debug){
                header('Content-Type: text/plain; charset=utf-8');
                echo $latex;exit;
            }
            return $latex;
        }
        return '';
    }

    protected static $_refs;

    public static function processBlock(DOMNode $body, $flags = 0)
    {
        $latex = '';
        $par = new PhpLatex_Filter_ParagraphList();
        foreach ($body->childNodes as $item) {
            switch ($item->nodeType) {
                case XML_TEXT_NODE:
                case XML_ENTITY_NODE:
                    self::_addToParagraph($par, $item);
                    break;

                case XML_ELEMENT_NODE:
                    switch (strtoupper($item->tagName)) {
                        case 'H1':
                        case 'H2':
                        case 'H3':
                        case 'H4':
                        case 'H5':
                        case 'H6':
                            $value = trim(self::getText($item));
                            if (!($flags & self::NO_HEADINGS)) {
                                $map = array(
                                    'H1' => 'section',
                                    'H2' => 'section',
                                    'H3' => 'subsection',
                                    'H4' => 'subsubsection',
                                    'H5' => 'paragraph',
                                    'H6' => 'subparagraph',
                                );

                                // TODO handle math mode \texorpdfstring

                                $value = '\\' . $map[strtoupper($item->tagName)] . '*{' . $value . '}' . "\n";

                                // find first id, if found, create label,
                                // analyze elements in document order
                                $elems = array($item);
                                while ($elem = array_shift($elems)) {
                                    if ($elem->nodeType === XML_ELEMENT_NODE) {
                                        $id = str_ireplace('ref:', '', $elem->getAttribute('id'));
                                        if (strlen($id)) {
                                            $value .= '\\label{ref:' . $id . '}' . "\n";
                                            break;
                                        }
                                        foreach ($elem->childNodes as $child) {
                                            $elems[] = $child;
                                        }
                                    }
                                }
                            }
                            $latex .= $par . $value;
                            $par->clear();
                            break;

                        case 'UL':
                        case 'OL':
                        case 'DL':
                            $latex .= $par . self::processList($item);
                            $par->clear();
                            break;

                        case 'TABLE':
                            $latex .= $par . self::processTable($item);
                            $par->clear();
                            break;

                        default:
                            self::_addToParagraph($par, $item);
                            break;
                    }
            }
        }

        if (count($par)) {
            $latex .= $par;
            $par->clear();
        }

        if ($flags & self::TRIM) { // trim only new lines
            $latex = str_replace("\n", '', $latex);
        }

        return $latex;
    }

    const BOLD          = 0x0001;
    const ITALIC        = 0x0002;
    const TELETYPE      = 0x0004;
    const UNDERLINE     = 0x0008;
    const NO_PARAGRAPH  = 0x0010;
    const TRIM          = 0x0020;
    const LINK          = 0x0040;
    const NO_HEADINGS   = 0x0080;

    // TODO table

    public static function processTable(DOMElement $table, $flags = 0)
    {
        // requires tabularx package
        $tbodies = array($table);
        foreach (self::getChildren($table, 'TBODY') as $tbody) {
            $tbodies[] = $tbody;
        }
        $ncols = 0;
        $content = '';
        while ($tbody = array_shift($tbodies)) {
            foreach (self::getChildren($tbody, 'TR') as $tr) {
                $tds = self::getChildren($tr, 'TD');
                $ncols = max($ncols, count($tds));
                $row = array();
                foreach ($tds as $td) {
                    $row[] = self::getText($td);
                }
                $row = implode(' & ', $row);
                if (strlen($row)) {
                    $content .= $row . '\\\\' . "\n";
                }
            }
        }

        $latex = '';
        if ($content) {
            if ($ncols === 2) {
                $colspec = 'Xr';
            } elseif ($ncols === 3) {
                $colspec = 'lXr';
            } else {
                $colspec = str_repeat('X', $ncols);
            }
            // TODO handle colspec -> borders, alignment
            $latex .= '\\vspace{5ex}' . "\n";
            $latex .= '\\begin{tabularx}{\textwidth}{' . $colspec . '}' . "\n";
            $latex .= $content;
            $latex .= '\\end{tabularx}' . "\n";
            $latex .= '\\vspace{5ex}' . "\n\n";
        }
        return $latex;
    }

    public static function getChildren(DOMNode $node, $tagName)
    {
        $children = array();
        if ($node->nodeType === XML_ELEMENT_NODE) {
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_ELEMENT_NODE && strtoupper($child->tagName) === $tagName) {
                    $children[] = $child;
                }
            }
        }
        return $children;
    }

    public static function processList(DOMElement $element, $flags = 0, $level = 0)
    {
        // TODO handle indented lists

        $tagName = strtoupper($element->tagName);
        if (!in_array($tagName, array('OL', 'UL', 'DL'))) {
            throw new InvalidArgumentException('Not a list: ' . $tagName);
        }

        // Lists in LaTeX can be 4 levels deep
        if ($level >= 4) {
            return self::getText($element, self::NO_PARAGRAPH | self::TRIM);
        }

        $env = null;

        if ($tagName === 'OL') {
            $env = 'enumerate';
        } elseif ($tagName === 'UL') {
            $env = 'itemize';
        } elseif ($tagName === 'DL') {
            $env = 'description';
        }

        $latex = '';

        // paragraphs in list item?
        $prevTag = null;
        foreach ($element->childNodes as $item) {
            if ($item->nodeType !== XML_ELEMENT_NODE) {
                continue;
            }
            $t = strtoupper($item->tagName);
            switch ($t) {
                case 'LI':
                case 'DD':
                    $text = self::processBlock($item, self::TRIM | self::NO_HEADINGS);
                    // there can be more than one paragraph in list item
                    $text = preg_replace('/\n[ \t]*\n+/', "\n\n", trim($text));

                    if ($t == 'LI' || ($t == 'DD' && (!$prevTag && $prevTag !== 'DT'))) {
                        $latex .= '    \\item ' . trim($text) . "\n";
                    } else {
                        $latex .= '    ' . trim($text) . "\n";
                    }
                    break;

                case 'DT':
                    $text = self::getText($item, self::TRIM | self::NO_PARAGRAPH);
                    $latex .= '    \\item';
                    if (strlen($text)) {
                        $latex .= '[{' . $text . '}]' . "\n";
                    }
                    break;

                default:
                    var_dumP($item);exit;
            }
            $prevTag = $t;
        }

        return sprintf("\\begin{%s}\n%s\\end{%s}\n\n", $env, $latex, $env);
    }

    public static function processLink(DOMElement $element, $flags = 0)
    {
        if ($flags & self::LINK) {
            // no nested links
            return;
        }

        $text = self::getText($element, self::NO_PARAGRAPH | self::LINK);
        if (strlen($text)) {
            $href = trim($element->getAttribute('href'));
            if (strlen($href)) {
                $label = PhpLatex_Utils::escape($text);
                if ($href{0} === '#') {
                    $id = substr($href, 1);
                    if (isset(self::$_refs[$id])) {
                        return '\\hyperref[{' . self::$_refs[$id] . '}]{' . $label . '}';
                    }
                    return;
                }
                return '\\href{' . PhpLatex_Utils::escape($href) . '}{' . $label . '}';
            }
        }
    }

    protected static function _addToParagraph(PhpLatex_Filter_ParagraphList $par, DOMNode $item, $flags = 0)
    {
        $cflags = $flags;
        switch ($item->nodeType) {
            case XML_TEXT_NODE:
                $par->addText(self::getTextValue($item));
                break;

            case XML_ENTITY_NODE:
                $par->addText(self::getTextValue($item));
                break;

            case XML_ELEMENT_NODE:
                switch (strtoupper($item->tagName)) {
                    case 'BR':
                        $par->breakLine();
                        break;

                    case 'STRONG':
                    case 'B':
                        $value = self::getText($item, $cflags | self::BOLD);
                        if (!($flags & self::BOLD) && strlen($value)) {
                            $value = '\\textbf{' . $value . '}';
                        }
                        $par->addText($value);
                        break;

                    case 'EM':
                    case 'I':
                        $value = self::getText($item, $cflags | self::ITALIC);
                        if (!($flags & self::ITALIC) && strlen($value)) {
                            $value = '\\textit{' . $value . '}';
                        }
                        $par->addText($value);
                        break;

                    case 'CODE':
                        $value = self::getText($item, $cflags | self::TELETYPE);
                        if (!($flags & self::TELETYPE) && strlen($value)) {
                            $value = '\\texttt{' . $value . '}';
                        }
                        $par->addText($value);
                        break;

                    case 'U':
                        $value = self::getText($item, $cflags | self::UNDERLINE);
                        if (!($flags & self::UNDERLINE) && strlen($value)) {
                            $value = '\\underline{' . $value . '}';
                        }
                        $par->addText($value);
                        break;

                    // TODO handle indented paragraphs
                    case 'P':
                        $par->newParagraph();
                        foreach ($item->childNodes as $child) {
                            self::_addToParagraph($par, $child);
                        }
                        break;

                    case 'A':
                        $par->addText(self::processLink($item, $flags));
                        break;

                    case 'SUB':
                        // requires \usepackage{fixltx2e} for releases prior to 2015/01/01
                        $par->addText('\\textsubscript{' . self::getText($item, $cflags | self::NO_PARAGRAPH) . '}');
                        break;

                    case 'SUP':
                        $par->addText('\\textsuperscript{' . self::getText($item, $cflags | self::NO_PARAGRAPH) . '}');
                        break;

                    default:
                        $par->addText(self::getText($item, $cflags));
                        break;
                }
                break;
        }

        return $par;
    }

    public static function getText(DOMNode $element, $flags = 0)
    {
        $par = new PhpLatex_Filter_ParagraphList();

        foreach ($element->childNodes as $item) {
            switch ($item->nodeType) {
                case XML_TEXT_NODE:
                case XML_ENTITY_NODE:
                    $par->addText(self::getTextValue($item));
                    break;

                case XML_ELEMENT_NODE:
                    self::_addToParagraph($par, $item, $flags);
                    break;
            }
        }

        return implode(' ', $par->toArray());
    }

    public static function getTextValue(DOMText $node)
    {
        $value = str_replace(array("\r\n", "\r"), "\n", $node->wholeText);
        $value = PhpLatex_Utils::escape($value);

        // replace UTF-8 characters with their counterparts if encoding is not UTF-8,
        // otherwise remove invalid UTF-8 characters
        if (in_array(self::$_outputEncoding, array('UTF-8', 'UTF8'), true)) {
            // regex taken from http://stackoverflow.com/questions/1401317/remove-non-utf8-characters-from-string
            $regex = '
            /
              (
                (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
                |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
                |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
                |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3
                ){1,100}                        # ...one or more times
              )
            | .                                 # anything else
            /x';
            $value = preg_replace($regex, '$1', $value);
        } else {
            $value = PhpLatex_Utils::escapeUtf8($value);
        }
        return $value;
    }
}

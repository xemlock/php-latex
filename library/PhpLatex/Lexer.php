<?php

class PhpLatex_Lexer
{
    const STATE_DEFAULT = 0;
    const STATE_BSLASH  = 1;
    const STATE_CONTROL = 2;
    const STATE_SPACE   = 3;

    const TYPE_TEXT     = 'text';
    const TYPE_SPACE    = 'space';
    const TYPE_CWORD    = 'cword';
    const TYPE_CSYMBOL  = 'csymbol';
    const TYPE_SPECIAL  = 'special';
    const TYPE_COMMENT  = 'comment';

    protected $_str;
    protected $_pos;

    protected $_line;
    protected $_column;

    protected $_pline;
    protected $_pcolumn;

    protected $_token;

    public function __construct($str)
    {
        $this->setString($str);
    }

    public function setString($str)
    {
        // skip leading and trailing whitespaces
        $str = trim($str);

        // perform initial transformations to mimic how TeX handles whitespaces.
        // Because of this transformations verbatim environments must be handled
        // elsewhere (i.e., replaced with placeholders before passing the input
        // to this lexer).

        // Unify newline character across platforms, replace tab with space
        $str = str_replace(
            array("\r\n", "\r", "\t"),
            array("\n", "\n", " "),
            (string) $str
        );

        // Replace ASCII control characters with spaces, so that token positions
        // remain unchanged
        $str = preg_replace(
            '/[\x{0000}-\x{0009}\x{000B}-\x{001F}\x{007F}]/u',
            ' ',
            $str
        );

        // echo '<pre>INTERNAL: ', (htmlspecialchars($str)), '</pre>';
        $this->_str = $str;
        $this->_pos = 0;

        $this->_line = 1;
        $this->_column = 0;
    }

    public function current()
    {
        return $this->_token;
    }

    public function next()
    {
        // This behavior can be illustrated by the following
        // tests:

        $state = self::STATE_DEFAULT;
        $buf = '';
        $n = strlen($this->_str);
        $p = null; // previous char

        while ($this->_pos < $n + 1) {
            $c = substr($this->_str, $this->_pos, 1);
            $this->_pos++;

            $this->_pcolumn = $this->_column;
            $this->_pline = $this->_line;

            $this->_column++;

            // substr('a', 1, 1) in PHP 5.x is false, whereas in 7+ is ''
            if ($c === false || $c === '') {
                $c = "\x00"; // artificial character after last char of input
            }

            // echo "STATE($state), CHAR(", $c == "\n" ? "\\n" : $c, "), BUF({$buf})\n";
            switch ($c) {
                case "\x00":
                    switch ($state) {
                        case self::STATE_DEFAULT:
                            if (strlen($buf)) {
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }
                            break;

                        case self::STATE_BSLASH:
                            break;

                        case self::STATE_CONTROL:
                            return $this->_setToken(self::TYPE_CWORD, $buf);

                        case self::STATE_SPACE:
                            // ignore trailing spaces
                            break;
                    }
                    break;

                case "\\":
                    switch ($state) {
                        case self::STATE_DEFAULT:
                            // if there is something in the buffer return it
                            // before switching state
                            if (strlen($buf)) {
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }
                            $state = self::STATE_BSLASH;
                            $buf = "\\";
                            $this->storeTokenPosition();
                            break;

                        case self::STATE_BSLASH:
                            return $this->_setToken(self::TYPE_CSYMBOL, '\\\\');

                        case self::STATE_CONTROL:
                            // end of command, unget char, return buffer
                            $this->_ungetChar();
                            return $this->_setToken(self::TYPE_CWORD, $buf);

                        case self::STATE_SPACE:
                            $this->_ungetChar();
                            return $this->_setSpaceToken($buf);
                    }
                    break;

                case " ":
                case "\n":
                    switch ($state) {
                        case self::STATE_DEFAULT:
                            if (strlen($buf)) {
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }
                            $state = self::STATE_SPACE;
                            $buf = $c;
                            $this->storeTokenPosition();
                            if ($c === "\n") {
                                $this->_line++;
                                $this->_column = 0;
                            }
                            // if ($c === "\n") { increment line count }
                            break;

                        case self::STATE_BSLASH:
                            $this->storeTokenPosition();
                            // if space then return control symbol, otherwise
                            // switch to default state and unget this char to
                            // be handler later (ignore this backslash)
                            if ($c === ' ') {
                                return $this->_setToken(self::TYPE_CSYMBOL, '\\ ');
                            }
                            $state = self::STATE_DEFAULT;
                            $this->_ungetChar();
                            break;

                        case self::STATE_CONTROL:
                            // end of control word
                            $this->_ungetChar();
                            return $this->_setToken(self::TYPE_CWORD, $buf);

                        case self::STATE_SPACE:
                            $buf .= $c;
                            if ($c === "\n") {
                                $this->_line++;
                                $this->_column = 0;
                            }
                            // if ($c === "\n") { increment line count }
                            break;
                    }
                    break;

                case '%':
                    switch ($state) {
                        case self::STATE_DEFAULT:
                            // there may be something in buffer, if so, return
                            // it before returning this token
                            if (strlen($buf)) {
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }

                            // http://en.wikibooks.org/wiki/LaTeX/Basics#Comments:
                            // When LaTeX encounters a % character while
                            // processing an input file, it ignores the rest of
                            // the current line, the line break, and all
                            // whitespace at the beginning of the next line.

                            // The above statement is not exactly true, as can
                            // be shown by the following example:
                            //   A% comment
                            //       B
                            // will be rendered as AB, whereas:
                            //   A% comment
                            //
                            //       B
                            // as:
                            //   A
                            //   B
                            // Comment-terminating newline and newline occuring
                            // after it (intermediate spaces are ignored) are
                            // interpreted as \par command.

                            $this->storeTokenPosition();

                            // at this point $this->_pos stores a position of
                            // the next character (a character that will be
                            // handled in next iteration)
                            if (false === ($pos = strpos($this->_str, "\n", $this->_pos))) {
                                // no LF found, point at position after the last
                                // character in input
                                $pos = $n;
                            }

                            // $pos points now at position of the comment ending
                            // character (typically LF).

                            $buf = substr($this->_str, $this->_pos - 1, $pos - ($this->_pos - 1));

                            // resume processing at the terminating LF, skipping
                            // spaces after comment must be done in parser
                            $this->_pos = $pos;

                            return $this->_setToken(self::TYPE_COMMENT, $buf);

                        case self::STATE_BSLASH:
                            return $this->_setToken(self::TYPE_CSYMBOL, '\\%');

                        case self::STATE_CONTROL:
                            // end of command name, unget char
                            $this->_ungetChar();
                            return $this->_setToken(self::TYPE_CWORD, $buf);

                        case self::STATE_SPACE:
                            $this->_ungetChar();
                            return $this->_setSpaceToken($buf);
                    }
                    break;

                case '}':
                case '{':
                case '[': // square brackets are considered special symbols, as
                case ']': // they delimit optional arguments
                // case '(': no reason why ordinary brackets should be
                // case ')': considered a special symbol
                case '~':
                case '^':
                case '_':
                case '&':
                case '#':
                case '$':
                    switch ($state) {
                        case self::STATE_DEFAULT:
                            // there may be something in buffer, if so, return
                            // it before returning this token
                            if (strlen($buf)) {
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }
                            // unescaped special character
                            $this->storeTokenPosition();
                            return $this->_setToken(self::TYPE_SPECIAL, $c);

                        case self::STATE_BSLASH:
                            // escaped special character
                            return $this->_setToken(self::TYPE_CSYMBOL, '\\' . $c);

                        case self::STATE_CONTROL:
                            // end of command name, unget char
                            $this->_ungetChar();
                            return $this->_setToken(self::TYPE_CWORD, $buf);

                        case self::STATE_SPACE:
                            $this->_ungetChar();
                            return $this->_setSpaceToken($buf);
                    }
                    break;

                default:
                    switch ($state) {
                        case self::STATE_DEFAULT:
                            if ($buf === '') {
                                $this->storeTokenPosition();
                            }
                            $buf .= $c;
                            break;

                        case self::STATE_BSLASH:
                            if ($this->_isAlpha($c)) {
                                $state = self::STATE_CONTROL;
                                $buf .= $c;
                            } else {
                                // single non-letter -> control symbol, i.e., \^
                                return $this->_setToken(self::TYPE_CSYMBOL, "\\" . $c);
                            }
                            break;

                        case self::STATE_CONTROL:
                            if ($this->_isAlpha($c)) {
                                $buf .= $c;
                            } else {
                                // not a letter, unget last char, return buffer
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_CWORD, $buf);
                            }
                            break;

                        case self::STATE_SPACE:
                            $this->_ungetChar();
                            return $this->_setSpaceToken($buf);
                    }
                    break;
            }

            // remember previous char
            $p = $c;
        }

        // close incomplete math environment

        // echo 'BUF: ', $buf, '</br>';

        return false;
    }

    protected function _ungetChar()
    {
        --$this->_pos;
        $this->_line = $this->_pline;
        $this->_column = $this->_pcolumn;
    }

    protected $_tokenPosition = null;

    protected function storeTokenPosition()
    {
        $this->_tokenPosition = array('line' => $this->_line, 'column' => $this->_column);
    }

    protected function _setToken($type, $value, $raw = null)
    {
        // printf("setToken(type = %s, value = %s, pos = %d)\n", $type, $value, $this->_pos);
        $position = $this->_tokenPosition;

        $token = array(
            'type' => $type,
            'value' => $value,
            'line' => $position ? $position['line'] : null,
            'column' => $position ? $position['column'] : null,
        );
        if (isset($raw)) {
            $token['raw'] = $raw; // raw whitespace value
        }
        return $this->_token = $token;
    }

    /**
     * Return token based on the contents of given whitespace string.
     *
     * Consume all whitespaces, if among them more than one LF is found,
     * return \par, otherwise append a single space to the buffer.
     * This is equivalent to the following text transformations:
     * 1. merge spaces into adjacent newlines
     * 2. merge multiple newlines into \par
     * 3. replace single newline with a space
     *
     * \par is equivalent to: #[ \t]*\n[ \t]*\n[ \t\n]*#
     *
     * @param  string $value
     * @return array
     */
    protected function _setSpaceToken($value)
    {
        assert(ctype_space($value));

        if (substr_count($value, "\n") > 1) {
            return $this->_setToken(self::TYPE_CWORD, '\\par', $value);
        }

        return $this->_setToken(self::TYPE_SPACE, ' ', $value);
    }

    /**
     * Locale independent check if string is non-empty and consists of
     * ASCII letters A-Za-z only.
     *
     * @param  string $str
     * @return bool
     */
    protected function _isAlpha($str)
    {
        if (0 < ($len = strlen($str))) {
            for ($i = 0; $i < $len; ++$i) {
                $c = substr($str, $i, 1);
                if (($c < 'a' || 'z' < $c) && ($c < 'A' || 'Z' < $c)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}

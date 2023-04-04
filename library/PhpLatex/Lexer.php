<?php

class PhpLatex_Lexer
{
    const EOF = "EOF";

    const STATE_DEFAULT = 0;
    const STATE_BSLASH  = 1;
    const STATE_CONTROL = 2;
    const STATE_SPACE   = 3;

    const TYPE_TEXT     = 'text';
    const TYPE_SPACE    = 'space';
    const TYPE_CWORD    = 'cword';
    const TYPE_CSYMBOL  = 'csymbol';
    const TYPE_SPECIAL  = 'special';

    /** @deprecated  */
    const TYPE_COMMENT  = 'comment';

    protected $_str;
    protected $_pos;

    protected $_line;
    protected $_column;

    protected $_pline;
    protected $_pcolumn;

    /**
     * @var array|null
     */
    protected $_token;

    protected $_state;

    /**
     * @var array|null
     */
    protected $_tokenPosition;

    public function __construct($str)
    {
        $this->setString($str);
    }

    public function setString($str)
    {
        // skip leading and trailing whitespaces
        $str = trim($str);

        // perform initial transformations to mimic how TeX handles whitespaces.
        // Because of these transformations verbatim environments must be handled
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

        $this->_str = $str;
        $this->_pos = 0;

        $this->_line = 1;
        $this->_column = 0;

        $this->_pline = null;
        $this->_pcolumn = null;

        $this->_token = null;
        $this->_tokenPosition = null;

        $this->_state = self::STATE_DEFAULT;
    }

    public function current()
    {
        return $this->_token;
    }

    /**
     * @return array|false
     */
    public function next()
    {
        $buf = '';

        do {
            $c = $this->_getChar();

            switch ($c) {
                case self::EOF:
                    switch ($this->_state) {
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
                    switch ($this->_state) {
                        case self::STATE_DEFAULT:
                            // if there is something in the buffer return it
                            // before switching state
                            if (strlen($buf)) {
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }
                            $this->_state = self::STATE_BSLASH;
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

                case ' ':
                case "\n":
                    switch ($this->_state) {
                        case self::STATE_DEFAULT:
                            if (strlen($buf)) {
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }
                            $this->_state = self::STATE_SPACE;
                            $buf = $c;
                            $this->storeTokenPosition();
                            if ($c === "\n") {
                                $this->_line++;
                                $this->_column = 0;
                            }
                            break;

                        case self::STATE_BSLASH:
                            $this->storeTokenPosition();
                            // if space then return control symbol, otherwise
                            // switch to default state and unget this char to
                            // be handler later (ignore this backslash)
                            if ($c === ' ') {
                                return $this->_setToken(self::TYPE_CSYMBOL, '\\ ');
                            }
                            $this->_state = self::STATE_DEFAULT;
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
                            break;
                    }
                    break;

                case '%':
                    switch ($this->_state) {
                        case self::STATE_DEFAULT:
                            // there may be something in buffer, if so, return
                            // it before returning this token
                            if (strlen($buf)) {
                                $this->_ungetChar();
                                return $this->_setToken(self::TYPE_TEXT, $buf);
                            }

                            // http://en.wikibooks.org/wiki/LaTeX/Basics#Comments:
                            // "When LaTeX encounters a % character while processing an input file, it
                            // ignores the rest of the current line, the line break, and all whitespace
                            // [newline excluded!] at the beginning of the next line."
                            // This behavior can be illustrated by the following example:
                            //   A% comment
                            //       B
                            // will be rendered as:
                            //   AB
                            // whereas:
                            //   A% comment
                            //
                            //       B
                            // as:
                            //   A
                            //   B
                            // Comment-terminating newline and newline occurring after it
                            // (intermediate spaces are ignored) are interpreted as \par command.

                            $this->storeTokenPosition();

                            return $this->_setToken(self::TYPE_SPECIAL, '%');

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

                // The following characters play a special role in LaTeX and are called special printing
                // characters, or simply special characters.
                // # $ % & ~ _ ^ \ { }
                // http://www.personal.ceu.hu/tex/specchar.htm
                case '}':
                case '{':
                case '~':
                case '^':
                case '_':
                case '&':
                case '#':
                case '$':
                case '[': // square brackets are considered special symbols, as
                case ']': // they delimit optional arguments
                    switch ($this->_state) {
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
                    switch ($this->_state) {
                        case self::STATE_DEFAULT:
                            if ($buf === '') {
                                $this->storeTokenPosition();
                            }
                            $buf .= $c;
                            break;

                        case self::STATE_BSLASH:
                            if ($this->_isAlpha($c)) {
                                $this->_state = self::STATE_CONTROL;
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
        } while ($c !== self::EOF);

        return false;
    }

    /**
     * @return string
     */
    protected function _getChar()
    {
        if ($this->_pos >= strlen($this->_str)) {
            return self::EOF; // artificial symbol denoting end of input
        }

        $c = substr($this->_str, $this->_pos, 1);
        $this->_pos++;

        $this->_pcolumn = $this->_column;
        $this->_pline = $this->_line;

        $this->_column++;

        return $c;
    }

    protected function _ungetChar()
    {
        if ($this->_pline === null) {
            throw new RuntimeException('Too many unget calls');
        }

        --$this->_pos;
        $this->_line = $this->_pline;
        $this->_column = $this->_pcolumn;

        $this->_pline = null;
        $this->_pcolumn = null;
    }

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
        $this->_state = self::STATE_DEFAULT;
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
        if (!ctype_space($value)) {
            throw new InvalidArgumentException('Whitespace value expected');
        }

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
        // ctype_alpha() is locale dependent so can't be used here
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

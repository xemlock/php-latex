<?php

class PhpLatex_Test_LexerTest extends PHPUnit_Framework_TestCase
{
    public function testTokens()
    {
        $input = '\[
    \begin{array}{c}
        \eta_1    \\\\
        \eta_{12}
    \end{array} % comment in math mode
\]

%% Comment in text mode
End.
';
        $lexer = new PhpLatex_Lexer($input);

        $tokens = array();
        while ($token = $lexer->next()) {
            $tokens[] = $token;
        }

        $this->assertEquals(array(
            array('type' => PhpLatex_Lexer::TYPE_CSYMBOL, 'value' => '\[', 'line' => 1, 'column' => 1),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n    ", 'line' => 1, 'column' => 3),
            array('type' => PhpLatex_Lexer::TYPE_CWORD, 'value' => '\begin', 'line' => 2, 'column' => 5),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '{', 'line' => 2, 'column' => 11),
            array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'array', 'line' => 2, 'column' => 12),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '}', 'line' => 2, 'column' => 17),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '{', 'line' => 2, 'column' => 18),
            array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'c', 'line' => 2, 'column' => 19),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '}', 'line' => 2, 'column' => 20),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n        ", 'line' => 2, 'column' => 21),
            array('type' => PhpLatex_Lexer::TYPE_CWORD, 'value' => '\eta', 'line' => 3, 'column' => 9),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '_', 'line' => 3, 'column' => 13),
            array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => '1', 'line' => 3, 'column' => 14),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => '    ', 'line' => 3, 'column' => 15),
            array('type' => PhpLatex_Lexer::TYPE_CSYMBOL, 'value' => '\\\\', 'line' => 3, 'column' => 19),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n        ", 'line' => 3, 'column' => 21),
            array('type' => PhpLatex_Lexer::TYPE_CWORD, 'value' => '\eta', 'line' => 4, 'column' => 9),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '_', 'line' => 4, 'column' => 13),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '{', 'line' => 4, 'column' => 14),
            array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => '12', 'line' => 4, 'column' => 15),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '}', 'line' => 4, 'column' => 17),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n    ", 'line' => 4, 'column' => 18),
            array('type' => PhpLatex_Lexer::TYPE_CWORD, 'value' => '\end', 'line' => 5, 'column' => 5),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '{', 'line' => 5, 'column' => 9),
            array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'array', 'line' => 5, 'column' => 10),
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '}', 'line' => 5, 'column' => 15),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 5, 'column' => 16),
            array('type' => PhpLatex_Lexer::TYPE_COMMENT, 'value' => '% comment in math mode', 'line' => 5, 'column' => 17),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n", 'line' => 5, 'column' => 39),
            array('type' => PhpLatex_Lexer::TYPE_CSYMBOL, 'value' => '\]', 'line' => 6, 'column' => 1),
            array('type' => PhpLatex_Lexer::TYPE_CWORD, 'value' => '\par', 'raw' => "\n\n", 'line' => 6, 'column' => 3),
            array('type' => PhpLatex_Lexer::TYPE_COMMENT, 'value' => '%% Comment in text mode', 'line' => 8, 'column' => 1),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n", 'line' => 8, 'column' => 24),
            array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'End.', 'line' => 9, 'column' => 1),
        ), $tokens);
    }
}

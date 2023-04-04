<?php

class PhpLatex_Test_LexerTest extends PHPUnit_Framework_TestCase
{
    public function testComment()
    {
        $this->assertTokenize(
            "A % comment\nB",
            array(
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'A', 'line' => 1, 'column' => 1),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 1, 'column' => 2),
                array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '%', 'line' => 1, 'column' => 3),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 1, 'column' => 4),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'comment', 'line' => 1, 'column' => 5),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n", 'line' => 1, 'column' => 12),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'B', 'line' => 2, 'column' => 1),
            )
        );
    }

    public function testCommentAtEnd()
    {
        $this->assertTokenize(
            "%",
            array(
                array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '%', 'line' => 1, 'column' => 1),
            )
        );
    }

    public function testEmptyComment()
    {
        $this->assertTokenize(
            "%\nA",
            array(
                array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '%', 'line' => 1, 'column' => 1),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n", 'line' => 1, 'column' => 2),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'A', 'line' => 2, 'column' => 1),
            )
        );
    }

    public function testCommentOnly()
    {
        $input = '% A';
        $tokens = array();
        $lexer = new PhpLatex_Lexer($input);
        while ($token = $lexer->next()) {
            $tokens[] = $token;
        }
        $this->assertEquals(array(
            array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '%', 'line' => 1, 'column' => 1),
            array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 1, 'column' => 2),
            array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'A', 'line' => 1, 'column' => 3),
        ), $tokens);
    }

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
        $this->assertTokenize(
            $input,
            array(
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
                array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '%', 'line' => 5, 'column' => 17),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 5, 'column' => 18),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'comment', 'line' => 5, 'column' => 19),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 5, 'column' => 26),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'in', 'line' => 5, 'column' => 27),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 5, 'column' => 29),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'math', 'line' => 5, 'column' => 30),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 5, 'column' => 34),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'mode', 'line' => 5, 'column' => 35),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n", 'line' => 5, 'column' => 39),
                array('type' => PhpLatex_Lexer::TYPE_CSYMBOL, 'value' => '\]', 'line' => 6, 'column' => 1),
                array('type' => PhpLatex_Lexer::TYPE_CWORD, 'value' => '\par', 'raw' => "\n\n", 'line' => 6, 'column' => 3),
                array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '%', 'line' => 8, 'column' => 1),
                array('type' => PhpLatex_Lexer::TYPE_SPECIAL, 'value' => '%', 'line' => 8, 'column' => 2),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 8, 'column' => 3),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'Comment', 'line' => 8, 'column' => 4),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 8, 'column' => 11),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'in', 'line' => 8, 'column' => 12),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 8, 'column' => 14),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'text', 'line' => 8, 'column' => 15),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => ' ', 'line' => 8, 'column' => 19),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'mode', 'line' => 8, 'column' => 20),
                array('type' => PhpLatex_Lexer::TYPE_SPACE, 'value' => ' ', 'raw' => "\n", 'line' => 8, 'column' => 24),
                array('type' => PhpLatex_Lexer::TYPE_TEXT, 'value' => 'End.', 'line' => 9, 'column' => 1),
            )
        );
    }

    protected function assertTokenize($input, array $expected)
    {
        $lexer = new PhpLatex_Lexer($input);
        $actual = array();
        while ($token = $lexer->next()) {
            $actual[] = $token;
        }
        $this->assertEquals($expected, $actual);
    }
}

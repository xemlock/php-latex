<?php

class PhpLatex_Test_ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PhpLatex_Parser
     */
    protected $parser;

    protected function setUp()
    {
        parent::setUp();
        $this->parser = new PhpLatex_Parser();
    }

    /**
     * @param string $input
     * @param string $expected
     * @dataProvider provideNewlines
     */
    public function testNewlines($input, $expected)
    {
        $tree = $this->parser->parse($input);
        $this->assertSame($expected, PhpLatex_Renderer_Abstract::toLatex($tree));
    }

    public function provideNewlines()
    {
        return array(
            array(
                "A\n \nB",
                'A\par B',
            ),
            array(
                "A\n { }\nB",
                'A { } B',
            ),
            array(
                "A\nB",
                'A B',
            ),
            array(
                "A\n % comment\nB",
                'A B',
            ),
            "LaTeX Error: There's no line here to end." => array(
                "A\n \n\\newline B",
                'A\par \newline B',
            ),
        );
    }

    public function testStarred()
    {
        $tree = $this->parser->parse('\\* \\section*{Foo} \\LaTeX*');
        $this->assertSame('\\* \\section*{Foo} \\LaTeX *', PhpLatex_Renderer_Abstract::toLatex($tree));
    }

    public function testSpaces()
    {
        $tree = $this->parser->parse('\\ \\, \\: \\;');
        $this->assertSame('\\ \\, \(\\:\) \(\\;\)', PhpLatex_Renderer_Abstract::toLatex($tree));
    }

    /**
     * @dataProvider provideLeftRight()
     * @param string $expected
     * @param string $input
     */
    public function testLeftRight($input, $expected = null)
    {
        $expected = $expected === null ? $input : $expected;
        $tree = $this->parser->parse($input);
        $this->assertSame($expected, PhpLatex_Renderer_Abstract::toLatex($tree));
    }

    public function provideLeftRight()
    {
        return array(
            'with parenthesis' => array(
                '\( \left( x^2 \right) \)',
                '\( \left( x^{2} \right) \)',
            ),
            'with curly brackets' => array(
                '\( \left\{ x^2 \right\} \)',
                '\( \left\{ x^{2} \right\} \)',
            ),
            'with angle brackets' => array(
                '\( \left< x^2 \right> \)',
                '\( \left< x^{2} \right> \)',
            ),
            'with no delimiters' => array(
                '\( \left x^2 \right \)',
                '\( \left. x^{2} \right. \)',
            ),
            'with spaces before parenthesis' => array(
                '\( \left ( x^2 \right ) \)',
                '\( \left( x^{2} \right) \)',
            ),
            'with \\rangle and \\langle' => array(
                '\( \left\langle x^2 \right \rangle \)',
                '\( \left\langle x^{2} \right\rangle \)',
            )
        );
    }

}

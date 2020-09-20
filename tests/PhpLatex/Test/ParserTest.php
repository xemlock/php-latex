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
}

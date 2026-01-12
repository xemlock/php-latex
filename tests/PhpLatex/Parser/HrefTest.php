<?php

class PhpLatex_Parser_HrefTest extends PHPUnit_Framework_TestCase
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
     * @see https://github.com/xemlock/php-latex/issues/9
     */
    function testParse()
    {
        $input = '\href{https://ja.wikipedia.org/wiki/%E9%9B%BB%E5%AD%90?utm_source=test&utm_medium=email}{electron}';
        $tree = $this->parser->parse($input);
        $this->assertSame($input, PhpLatex_Renderer_Abstract::toLatex($tree));
    }

    function testSpecialSymbols()
    {
        $input = '\url{https://test.com/~user{a}$2%20x#test}';
        $tree = $this->parser->parse($input);
        $this->assertSame($input, PhpLatex_Renderer_Abstract::toLatex($tree));
    }

    function testBackslash() {
        $input = '\href{run:C:\path\to\script.bat}{File}';
        $tree = $this->parser->parse($input);
        $this->assertSame('\href{run:C:\\\\path\\\\to\\\\script.bat}{File}', PhpLatex_Renderer_Abstract::toLatex($tree));
    }
}

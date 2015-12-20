<?php

class PhpLatex_Renderer_HtmlTest extends PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        $parser = new PhpLatex_Parser();
        $tree = $parser->parse('
            \textit{\textbf{Italic \textup{bold} text}}
        ');

        $renderer = new PhpLatex_Renderer_Html();
        $html = $renderer->render($tree);

        $this->assertEquals('<i><b>Italic <span style="font-style:normal">bold</span> text</b></i>', $html);
    }
}

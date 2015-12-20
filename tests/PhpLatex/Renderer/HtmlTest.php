<?php

class PhpLatex_Renderer_HtmlTest extends PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        $renderer = new PhpLatex_Renderer_Html();
        $html = $renderer->render('
            \textit{\textbf{Italic \textup{bold} text}}
        ');

        $this->assertEquals('<i><b>Italic <span style="font-style:normal">bold</span> text</b></i>', $html);
    }
}

<?php

class PhpLatex_Test_Renderer_HtmlTest extends PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        $renderer = new PhpLatex_Renderer_Html();
        $html = $renderer->render('
            \textit{\textbf{Italic \textup{bold} text}}
        ');

        $this->assertEquals('<i><b>Italic <span style="font-style:normal">bold</span> text</b></i>', $html);
    }

    public function testSpaces()
    {
        $renderer = new PhpLatex_Renderer_Html();
        $html = $renderer->render('
            A B

            A\ B

            A\,B

            A\enspace{}B

            A\quad B
        ');

        $this->assertEquals('A B<br/><br/>A&nbsp;B<br/><br/>A&thinsp;B<br/><br/>A&ensp;B<br/><br/>A&emsp;B', $html);
    }

    public function testIssue4()
    {
        $renderer = new PhpLatex_Renderer_Html();
        $html = $renderer->render('
            \textbf{Should not be}  joined

            \begin{tabular}{l}
            \textbf{Should not be}  joined
            \end{tabular}
        ');

        $this->assertEquals(
            '<b>Should not be</b> joined<br/><br/><table class="table"><tr><td style="text-align:left"><b>Should not be</b> joined</td></tr></table>',
            $html
        );
    }
}

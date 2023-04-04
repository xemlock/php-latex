<?php

class PhpLatex_Test_Renderer_AbstractTest extends PHPUnit_Framework_TestCase
{
    public function testTabular()
    {
        $input = '
\begin{tabular}{ |c|c|c| }
  \hline
  cell1 & cell2 & cell3 \\\\
  cell4 & cell5 & cell6 \\\\
  cell7 & cell8 & cell9 \\\\
  \hline
\end{tabular}

\begin{tabular}c
cell 1
\end{tabular}

{\huge Foobar}
';
        $parser = new PhpLatex_Parser();
        $output = PhpLatex_Renderer_Abstract::toLatex($parser->parse($input));

        $this->assertEquals(
'\begin{tabular}{ |c|c|c| }
 \hline cell1 & cell2 & cell3 \\\\ cell4 & cell5 & cell6 \\\\ cell7 & cell8 & cell9 \\\\ \hline' . ' ' . '
\end{tabular}\par \begin{tabular}{c}
 cell 1'. ' ' . '
\end{tabular}\par {\huge Foobar}', $output);
    }

    /**
     * @see https://github.com/xemlock/php-latex/issues/6
     */
    public function testIssue6()
    {
        $input = '\[
    \begin{array}{c}
        \eta_1    \\\\
        \eta_{12} \\\\
        \eta_{21} \\\\
        \eta_2
    \end{array} % comment is here
\]';

        $parser = new PhpLatex_Parser();
        $output = PhpLatex_Renderer_Abstract::toLatex($parser->parse($input));

        $this->assertEquals('\[ \begin{array}{c}
 \eta _{1} \\\\ \eta _{12} \\\\ \eta _{21} \\\\ \eta _{2}' . ' ' . '
\end{array} \]', $output);
    }
}

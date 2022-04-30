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
}

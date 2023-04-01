<?php

class PhpLatex_PdfLatexTest extends PHPUnit_Framework_TestCase
{
    public function testParseCompilerInfo()
    {
        $pdflatex = new PhpLatex_PdfLatex();

        $version = <<<END
This is LuaTeX, Version beta-0.80.0 (TeX Live 2015/Debian) (rev 5238)

Execute  'luatex --credits'  for credits and version details.

There is NO warranty. Redistribution of this software is covered by
the terms of the GNU General Public License, version 2 or (at your option)
any later version. For more information about these matters, see the file
named COPYING and the LuaTeX source.

Copyright 2015 Taco Hoekwater, the LuaTeX Team.
END;

        $this->assertEquals(
            array(
                'engine' => 'LuaTeX',
                'version' => 'beta-0.80.0 (TeX Live 2015/Debian) (rev 5238)',
            ),
            $pdflatex->_parseCompilerInfo($version)
        );

        $version = <<<END
pdfTeX 3.14159265-2.6-1.40.21 (TeX Live 2020/Debian)
kpathsea version 6.3.2
Copyright 2020 Han The Thanh (pdfTeX) et al.
There is NO warranty.  Redistribution of this software is
covered by the terms of both the pdfTeX copyright and
the Lesser GNU General Public License.
For more information about these matters, see the file
named COPYING and the pdfTeX source.
Primary author of pdfTeX: Han The Thanh (pdfTeX) et al.
Compiled with libpng 1.6.37; using libpng 1.6.37
Compiled with zlib 1.2.11; using zlib 1.2.11
Compiled with xpdf version 4.02
END;
        $this->assertEquals(
            array(
                'engine' => 'pdfTeX',
                'version' => '3.14159265-2.6-1.40.21 (TeX Live 2020/Debian)',
            ),
            $pdflatex->_parseCompilerInfo($version)
        );

        $version = <<<END
XeTeX 3.14159265-2.6-0.999992 (TeX Live 2020/Debian)
kpathsea version 6.3.2
Copyright 2020 SIL International, Jonathan Kew and Khaled Hosny.
There is NO warranty.  Redistribution of this software is
covered by the terms of both the XeTeX copyright and
the Lesser GNU General Public License.
For more information about these matters, see the file
named COPYING and the XeTeX source.
Primary author of XeTeX: Jonathan Kew.
Compiled with ICU version 67.1; using 67.1
Compiled with zlib version 1.2.11; using 1.2.11
Compiled with FreeType2 version 2.10.4; using 2.10.4
Compiled with Graphite2 version 1.3.14; using 1.3.14
Compiled with HarfBuzz version 2.7.4; using 2.7.4
Compiled with libpng version 1.6.37; using 1.6.37
Compiled with poppler version 0.68.0
Compiled with fontconfig version 2.13.1; using 2.13.1
END;
        $this->assertEquals(
            array(
                'engine' => 'XeTeX',
                'version' => '3.14159265-2.6-0.999992 (TeX Live 2020/Debian)',
            ),
            $pdflatex->_parseCompilerInfo($version)
        );
    }

    // This is XeTeX, Version 3.141592653-2.6-0.999993 (TeX Live 2021) (preloaded format=xelatex 2021.9.30)
    // This is LuaHBTeX, Version 1.13.2 (TeX Live 2021)  (format=lualatex 2021.9.30)
    // This is pdfTeX, Version 3.141592653-2.6-1.40.23 (TeX Live 2021) (preloaded format=latex 2021.9.30)
}

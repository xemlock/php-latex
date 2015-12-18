<?php

class PhpLatex_PdfLatex
{
    protected $_texmfhome;

    protected $_buildPath;

    protected $_log;

    protected $_pdflatexBinary;

    public function getPdflatexBinary()
    {
        // lstat(./pdflatex) failed ...
        // ./pdflatex: No such file or directory
        // pdflatex: ../../../texk/kpathsea/progname.c:316: remove_dots: Assertion `ret' failed.
        // Aborted

        // Solution: Use full path to the pdflatex binary

        if ($this->_pdflatexBinary === null) {
            $pdflatex = 'pdflatex';
            if (Zefram_Os::isWindows()) {
                $pdflatex .= '.exe';
            }
            $this->_pdflatexBinary = Zefram_Os::pathLookup($pdflatex);
        }
        return $this->_pdflatexBinary;
    }

    public function setBuildPath($path)
    {
        if (!is_dir($path) || !is_writable($path)) {
            throw new InvalidArgumentException('Path cannot be used as a build directory');
        }
        $this->_buildPath = rtrim(realpath($path), '/') . '/';
        return $this;
    }

    public function getBuildPath()
    {
        if (empty($this->_buildPath)) {
            throw new Exception('BuildPath is not configured');
        }
        return $this->_buildPath;
    }

    public function compile($script, $module = null, array $vars = null, array $files = null)
    {
        $conv = require dirname(__FILE__) . '/latex_utf8.php';
        $conv = array_flip($conv);

        $this->_log = null;

        if (is_array($module)) {
            $files = $vars;
            $vars = $module;
            $module = null;
        }

        $buildPath = $this->getBuildPath();
        $output = $this->_render($script, $module, $vars);

        $output = strtr($output, $conv);
        $output = preg_replace('/[^\t\n\r\x20-\x7E]/', '', $output);

        $key = 'pdflatex/' . md5($output);
        $basePath = $buildPath . $key . '/output';
        $pdf = $basePath . '.pdf';

        if (is_file($pdf)) {
            return $pdf;
        }

        if (!is_dir($buildPath . $key)) {
            mkdir($buildPath . $key, 0777, true);
        }

        foreach ((array) $files as $path) {
            // TODO handle Windows
            if (!is_file($buildPath . $key . '/' . basename($path))) {
                if (!@symlink($path, $buildPath . $key . '/' . basename($path))) {
                    copy($path, $buildPath . $key . '/' . basename($path));
                }
            }
        }

        $tex = $basePath . '.tex';
        file_put_contents($tex, $output);

        $cwd = getcwd();
        chdir($buildPath . $key);

        Zefram_Os::setEnv('TEXMFHOME', $this->_texmfhome);

        $pdflatex =  $this->getPdflatexBinary();

        $cmd = "$pdflatex -interaction nonstopmode -halt-on-error -file-line-error $tex";
        $log = `$cmd`;
        `$cmd 2>&1`;

        $log = str_replace(array("\r\n", "\r"), "\n", $log);
        $log = str_replace(array(
            $buildPath . $key . '/',
            wordwrap('(' . $buildPath . $key . '/', 79, "\n", true),
            wordwrap($buildPath . $key . '/', 79, "\n", true),
        ), array('', '('), $log);

        $this->_log = __CLASS__ . ' ' . $key . "\n\n" . $log;

        chdir($cwd);

        // if document body is empty a 0-length file is generated
        if (is_file($pdf) && filesize($pdf)) {
            return $pdf;
        }

        return false;
    }

    public function getLog()
    {
        return (string) $this->_log;
    }

    public function setTexmfhome($texmfhome)
    {
        $this->_texmfhome = (string) $texmfhome;
        return $this;
    }
}

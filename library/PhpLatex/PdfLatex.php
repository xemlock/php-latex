<?php

// TODO add options, such as changing PDF version in xelatex -output-driver="xdvipdfmx -V4"
class PhpLatex_PdfLatex
{
    const TEXMFHOME = 'TEXMFHOME';

    /**
     * @var string
     */
    protected $_texmfhome;

    /**
     * @var string
     */
    protected $_buildDir;

    protected $_log;

    /**
     * @var string
     */
    protected $_pdflatexBinary;

    public function getPdflatexBinary()
    {
        // lstat(./pdflatex) failed ...
        // ./pdflatex: No such file or directory
        // pdflatex: ../../../texk/kpathsea/progname.c:316: remove_dots: Assertion `ret' failed.
        // Aborted

        // Solution: Use full path to the pdflatex binary
        if ($this->_pdflatexBinary === null) {
            $this->_pdflatexBinary = $this->findPdflatexBinary();
        }
        return $this->_pdflatexBinary;
    }

    public function setPdflatexBinary($path)
    {
        if (!file_exists($path)) {
            throw new Exception('Pdflatex binary not found: ' . $path);
        }
        if (!is_executable($path)) {
            throw new Exception('Pdflatex binary is not executable: ' . $path);
        }
        $this->_pdflatexBinary = realpath($path);
        return $this;
    }

    public function findPdflatexBinary()
    {
        $files = array('pdflatex'); // xelatex perhaps?

        foreach ($files as $file) {
            if (stripos(PHP_OS, 'Win') !== false) {
                $file .= '.exe';
            }

            $path = getenv('PATH');
            $dirs = explode(PATH_SEPARATOR, $path);
            array_unshift($dirs, getcwd());

            foreach ($dirs as $dir) {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (file_exists($path)) {
                    return $path;
                }
            }
        }

        throw new Exception('Unable to find pdflatex binary');
    }

    public function setBuildDir($path)
    {
        if (!is_dir($path)) {
            throw new InvalidArgumentException('Path is not a directory: ' . $path);
        }
        if (!is_writable($path)) {
            throw new InvalidArgumentException('Path is not writable: ' . $path);
        }
        $this->_buildDir = rtrim(realpath($path), '/') . '/';
        return $this;
    }

    public function getBuildDir()
    {
        if (empty($this->_buildDir)) {
            $this->setBuildDir(sys_get_temp_dir());
        }
        return $this->_buildDir;
    }

    public function compile($file, array $files = null)
    {
        $this->_log = null;

        $cwd = getcwd();
        $dir = dirname($file);

        foreach ((array) $files as $path) {
            // TODO handle Windows
            if (!is_file($dir . '/' . basename($path))) {
                if (!@symlink($path, $dir . '/' . basename($path))) {
                    copy($path, $dir . '/' . basename($path));
                }
            }
        }

        chdir($dir);

        $pdflatex = $this->getPdflatexBinary();

        $texmfhome = getenv(self::TEXMFHOME);
        $this->_setEnv(self::TEXMFHOME, $this->_texmfhome);

        $cmd = "$pdflatex -interaction nonstopmode -halt-on-error -file-line-error $file";
        $log = `$cmd`;
        `$cmd 2>&1`;

        // process log so that paths are not given away
        $log = str_replace(array("\r\n", "\r"), "\n", $log);
        $log = str_replace(array(
            $dir . '/',
            wordwrap('(' . $dir . '/', 79, "\n", true),
            wordwrap($dir . '/', 79, "\n", true),
        ), array('', '('), $log);

        $this->_log = __CLASS__ . ' ' . $file . "\n\n" . $log;

        chdir($cwd);
        $this->_setEnv(self::TEXMFHOME, $texmfhome);

        $output = sprintf('%s/%s.pdf', $dir, basename($file, '.tex'));

        // if document body is empty a 0-length file is generated
        if (is_file($output) && filesize($output)) {
            return $output;
        }

        throw new Exception(sprintf('Unable to compile file \'%s\'', $file));
    }

    public function compileString($script, array $files = null)
    {
        $buildDir = $this->getBuildDir();

        $key = 'pdflatex/' . md5($script);
        $basePath = $buildDir . $key . '/output';
        $pdf = $basePath . '/output.pdf';

        if (is_file($pdf)) {
            return $pdf;
        }

        if (!is_dir($buildDir . $key)) {
            mkdir($buildDir . $key, 0777, true);
        }

        $tex = $basePath . '.tex';
        file_put_contents($tex, $script);

        return $this->compile($tex, $files);
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

    protected function _setEnv($key, $value)
    {
        // putenv/getenv and $_ENV are completely distinct environment stores
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

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
     * @var array
     */
    protected $_compiler;

    /**
     * @return string
     */
    public function getPdflatexBinary()
    {
        // lstat(./pdflatex) failed ...
        // ./pdflatex: No such file or directory
        // pdflatex: ../../../texk/kpathsea/progname.c:316: remove_dots: Assertion `ret' failed.
        // Aborted

        // Solution: Use the full path to the pdflatex binary
        if ($this->_compiler === null) {
            $this->setPdflatexBinary($this->findPdflatexBinary());
        }
        return $this->_compiler['path'];
    }

    public function setPdflatexBinary($path)
    {
        // Can't use file_exists() / is_executable(), because if open_basedir ini setting is in
        // effect, file won't be reported as existing/executable, but the binary itself can still
        // exist outside the open_basedir, and be executable.
        exec(escapeshellarg($path) . ' -version 2>&1', $output, $error);

        if ($error) {
            throw new InvalidArgumentException('Unable to execute pdflatex binary: ' . $path);
        }

        $compiler = $this->_parseCompilerInfo($output[0]);
        if (!$compiler) {
            throw new InvalidArgumentException('Unrecognized pdflatex -version output');
        }

        $this->_compiler = array(
            'path' => $path, // open_basedir may be in effect, don't use realpath()
            'engine' => $compiler['engine'],
            'version' => $compiler['version'],
        );

        return $this;
    }

    /**
     * @param string $version
     * @internal This function is not part of the public api.
     */
    public function _parseCompilerInfo($version)
    {
        if (preg_match("/(?P<engine>\S*?TeX) (?P<version>\d[^\n]+)/i", $version, $match)) {
            return array('engine' => $match['engine'], 'version' => $match['version']);
        }
        if (preg_match("/(?P<engine>\S*?TeX), Version (?P<version>[^\n]+)/i", $version, $match)) {
            return array('engine' => $match['engine'], 'version' => $match['version']);
        }
        return false;
    }

    public function findPdflatexBinary()
    {
        $files = array('pdflatex');

        $path = getenv('PATH');
        $dirs = explode(PATH_SEPARATOR, $path);
        array_unshift($dirs, getcwd());

        // WIN32 WINNT Windows CYGWIN_NT-5.1
        $isWindows = stripos(PHP_OS, 'WIN') === 0 || stripos(PHP_OS, 'CYGWIN') === 0;

        foreach ($files as $file) {
            if ($isWindows) {
                $file .= '.exe';
            }

            foreach ($dirs as $dir) {
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (file_exists($path) && is_executable($path)) {
                    return $path;
                }
            }
        }

        throw new Exception('Unable to locate pdflatex binary');
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

        $pdflatex = $this->getPdflatexBinary();

        $texmfhome = getenv(self::TEXMFHOME);
        $this->_setEnv(self::TEXMFHOME, $this->_texmfhome);

        chdir($dir);
        $cmd = "$pdflatex -interaction nonstopmode -halt-on-error -file-line-error $file";
        $log = `$cmd`;
        `$cmd 2>&1`;
        chdir($cwd);

        $this->_setEnv(self::TEXMFHOME, $texmfhome);

        // process log so that paths are not given away
        $log = str_replace(array("\r\n", "\r"), "\n", $log);
        $log = str_replace(array(
            $dir . '/',
            wordwrap('(' . $dir . '/', 79, "\n", true),
            wordwrap($dir . '/', 79, "\n", true),
        ), array('', '('), $log);

        $this->_log = __CLASS__ . ' ' . $file . "\n\n" . $log;

        $output = sprintf('%s/%s.pdf', $dir, basename($file, '.tex'));

        // if document body is empty a 0-length file is generated
        if (is_file($output) && filesize($output)) {
            return $output;
        }

        throw new Exception(sprintf('Unable to compile file \'%s\'', $file));
    }

    /**
     * Compile string to a PDF document
     *
     * @param $script String containing LaTeX document source
     * @param array $files
     * @return string Path to compiled PDF document
     * @throws Exception
     */
    public function compileString($script, array $files = null)
    {
        $buildDir = $this->getBuildDir() . 'pdflatex/' . md5($script);
        $output = $buildDir . '/output.pdf';

        if (is_file($output)) {
            return $output;
        }

        if (!is_dir($buildDir)) {
            if (!@mkdir($buildDir, 0777, true)) {
                throw new Exception(sprintf(
                    'Unable to create script build directory: %s',
                    $buildDir
                ));
            }
        }

        if (!is_writable($buildDir)) {
            throw new Exception(sprintf(
                'Script build directory is not writable: %s',
                $buildDir
            ));
        }

        $scriptFile = $buildDir . '/output.tex';
        file_put_contents($scriptFile, $script);

        return $this->compile($scriptFile, $files);
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

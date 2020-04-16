<?php

namespace Pyro\Platform\Support;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;

class BladeString
{
    /** @var \Illuminate\View\Compilers\BladeCompiler */
    protected $compiler;

    /** @var \Illuminate\Filesystem\Filesystem */
    protected $fs;

    /** @var string */
    protected $cachePath;

    public function __construct(Filesystem $fs)
    {
        $this->fs        = $fs;
        $this->cachePath = storage_path('blade-extensions');
    }

    /**
     * {@inheritdoc}
     */
    public function compileString($string, array $vars = [])
    {
        if (empty($vars)) {
            return $this->getCompiler()->compileString($string);
        }
        $fileName = uniqid('compileString', true) . '.php';
        $filePath = $this->cachePath . DIRECTORY_SEPARATOR . $fileName;
        $string   = $this->getCompiler()->compileString($string);
        $this->fs->put($filePath, $string);
        $compiledString = $this->getCompiledContent($filePath, $vars);
        $this->fs->delete($filePath);

        return $compiledString;
    }

    /**
     * getCompiledContent method.
     *
     * @param       $filePath
     * @param array $vars
     *
     * @return string
     */
    protected function getCompiledContent($filePath, array $vars = [])
    {
        if (is_array($vars) && ! empty($vars)) {
            extract($vars, EXTR_OVERWRITE);
        }
        ob_start();
        include $filePath;
        $var = ob_get_contents();
        ob_end_clean();

        return $var;
    }

    /**
     * getCompiler method.
     *
     * @return \Illuminate\View\Compilers\BladeCompiler
     */
    public function getCompiler()
    {
        if ( ! isset($this->compiler)) {
            if ($this->fs->exists($this->cachePath) === false) {
                $this->fs->makeDirectory($this->cachePath);
            }
            $this->compiler = new BladeCompiler($this->fs, $this->cachePath);
        }

        return $this->compiler;
    }

    public function setCompiler($compiler)
    {
        $this->compiler = $compiler;
        return $this;
    }

    public function getCachePath()
    {
        return $this->cachePath;
    }

    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
        return $this;
    }

}

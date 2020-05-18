<?php

namespace Pyro\Platform\Support;

use Illuminate\Contracts\Cache\Repository;
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

    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    public function __construct(Filesystem $fs, Repository $cache)
    {
        $this->fs        = $fs;
        $this->cachePath = storage_path('blade-string');
        $this->cache     = $cache;
    }

    public function compile($string, array $vars = [], $cache = true)
    {
        if ( ! $cache) {
            return $this->compileString($string, $vars);
        }
        $key = 'bladestring:' . md5($string) . '_' . md5(serialize($vars));
        return $this->cache->remember($key,60, function () use ($string, $vars) {
            return $this->compileString($string, $vars);
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function compileString($string, array $vars = [])
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

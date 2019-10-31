<?php

namespace Pyro\Platform\Webpack;

use Anomaly\Streams\Platform\Addon\Addon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class WebpackAddon implements Arrayable
{
    /** @var array|string[]|\Illuminate\Support\Collection */
    public $scripts = [];

    /** @var array|string[]|\Illuminate\Support\Collection */
    public $styles = [];

    /** @var string */
    public $composerName;

    /** @var string */
    public $composerType;

    /** @var string */
    public $name;

    /** @var string */
    public $firstName;

    /** @var string */
    public $firstNameSnake;

    /** @var string */
    public $lastName;

    /** @var string */
    public $lastNameSnake;

    /** @var string */
    public $entryName;

    /** @var string */
    public $relativePath;

    /** @var string */
    public $streamNamespace;

    /** @var \Anomaly\Streams\Platform\Addon\Addon */
    public $streamAddon;

    /** @var int */
    public $sorted;

    protected $arrayableProperties = [
        'addon',
        'package',
        'scripts',
        'styles',
        'composerName',
        'composerType',
        'name',
        'firstName',
        'firstNameSnake',
        'lastName',
        'lastNameSnake',
        'entryName',
        'relativePath',
        'addonNamespace',
        'sorted',
    ];

    /** @var \Pyro\Platform\Webpack\Webpack */
    protected $webpack;

    /**
     * WebpackAddon constructor.
     *
     * @param \Pyro\Platform\Webpack\Webpack $webpack
     */
    public function __construct(Webpack $webpack)
    {
        $this->webpack = $webpack;
    }

    public function getRelativePath()
    {
        return $this->relativePath;
    }

    public function setRelativePath(string $relativePath): WebpackAddon
    {
        $this->relativePath = $relativePath;
        $this->setStreamNamespace($this->makeAddonNamespace($relativePath));
        return $this;
    }

    public function getStreamNamespace()
    {
        return $this->streamNamespace;
    }

    public function setStreamNamespace(?string $streamNamespace)
    {
        $this->streamNamespace = $streamNamespace;
        return $this;
    }

    public function isStreamNamespace($streamNamespace)
    {
        return $this->streamNamespace === $streamNamespace;
    }

    public function getStreamAddon()
    {
        return $this->streamAddon;
    }

    public function setStreamAddon(Addon $streamAddon)
    {
        $this->streamAddon = $streamAddon;
        return $this;
    }

    public function getScripts()
    {
        return $this->scripts;
    }

    public function setScripts($scripts)
    {
        $this->scripts = Collection::wrap($scripts);
        return $this;
    }

    public function getStyles()
    {
        return $this->styles;
    }

    public function setStyles($styles)
    {
        $this->styles = Collection::wrap($styles);
        return $this;
    }

    public function getScriptUrls()
    {
        return $this->scripts->map(function ($script) {
            return $this->webpack->getPublicPath() . $script;
        });
    }

    public function getStyleUrls()
    {
        return $this->styles->map(function ($style) {
            return $this->webpack->getPublicPath() . $style;
        });
    }

    public function getComposerName()
    {
        return $this->composerName;
    }

    public function setComposerName(?string $composerName)
    {
        $this->composerName = $composerName;
        return $this;
    }

    public function getComposerType()
    {
        return $this->composerType;
    }

    public function setComposerType(?string $composerType)
    {
        $this->composerType = $composerType;
        return $this;
    }

    public function isComposerType($composerType)
    {
        return $this->composerType === $composerType;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
        [ $this->firstName, $this->lastName ] = explode('/', $name);
        $this->firstNameSnake = str_replace('@', '', Str::snake($this->firstName));
        $this->firstNameSnake = str_replace('-', '_', $this->firstNameSnake);
        $this->lastNameSnake  = Str::snake($this->lastName);
        $this->lastNameSnake  = str_replace('-', '_', $this->lastNameSnake);
        $this->entryName      = $this->firstNameSnake . '__' . $this->lastNameSnake;
        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getFirstNameSnake()
    {
        return $this->firstNameSnake;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getLastNameSnake()
    {
        return $this->lastNameSnake;
    }

    public function getEntryName()
    {
        return $this->entryName;
    }

    public function getSorted()
    {
        return $this->sorted;
    }

    public function setSorted(int $sorted)
    {
        $this->sorted = $sorted;
        return $this;
    }

    public function isStreamAddon()
    {
        return $this->isComposerType('stream-addon');
    }

    public function toArray()
    {
        return collect($this->arrayableProperties)->mapWithKeys(function ($property) {
            return [ $property => $this->{$property} ];
        })->toArray();
    }

    protected function makeAddonNamespace($path)
    {
        $vendor = strtolower(basename(dirname($path)));
        $slug   = strtolower(substr(basename($path), 0, strpos(basename($path), '-')));
        $type   = strtolower(substr(basename($path), strpos(basename($path), '-') + 1));

        return "{$vendor}.{$type}.{$slug}";
    }

    public function hasStreamAddon()
    {
        return $this->streamAddon instanceof Addon;
    }

}

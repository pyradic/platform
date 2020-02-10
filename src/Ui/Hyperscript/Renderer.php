<?php

namespace Pyro\Platform\Ui\Hyperscript;

use DOMDocument;

class Renderer
{
    protected $document;

    public function __construct()
    {
        $this->document = new DOMDocument();
    }

    public function toDOM(Element $el, $recursive = true)
    {
        $element = $this->document->createElement($el->tag, $el->text);
        foreach ($el->attributes as $k => $v) {
            $element->setAttribute($k, $v);
        }
        if ($recursive) {
            foreach ($el->children as $child) {
                $childElement = static::toDOM($child, true);
                $element->appendChild($childElement);
            }
        }
        return $element;
    }

    public function render(Element $el)
    {
        $this->document->appendChild($this->toDOM($el));
        return $this->document->saveHTML();
    }

    public function setPrettyPrint(bool $pretty)
    {
        $this->document->formatOutput       = $pretty;
        $this->document->preserveWhiteSpace = $pretty;
        return $this;
    }
}

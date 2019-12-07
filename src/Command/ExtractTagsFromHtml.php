<?php

namespace Pyro\Platform\Command;

class ExtractTagsFromHtml
{
    /** @var string */
    protected $tag;

    /** @var string */
    protected $html;

    /**
     * ExtractTagsFromHtml constructor.
     *
     * @param string $tag
     * @param string $html
     */
    public function __construct(string $tag, string $html)
    {
        $this->tag  = $tag;
        $this->html = $html;
    }

    public function handle()
    {
        $options = LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD;
        $source  = new \DOMDocument();
        $source->loadHTML("<div>{$this->html}</div>", $options);
        $extracted               = new \DOMDocument();
        $extracted->formatOutput = true;
        $extracted->loadHTML('<div></div>', $options);

        /** @var \DOMNodeList|\DOMNode[] $elements */
        $elements = $source->getElementsByTagName($this->tag);
        $remove   = [];
        foreach ($elements as $i => $element) {
            $remove[] = $element;
            $copied   = $extracted->importNode($element->cloneNode(true), true);
            $extracted->documentElement->appendChild($copied);
        }
        foreach ($remove as $item) {
            $item->parentNode->removeChild($item);
        }
        $resultHtml = '';
        foreach ($source->firstChild->childNodes as $node) {
            $resultHtml .= $source->saveHTML($node);
        }
        $extractedHtml = '';
        foreach ($extracted->firstChild->childNodes as $node) {
            $extractedHtml .= $extracted->saveHTML($node);
        }

        return new ExtractedTags($resultHtml,$extractedHtml);
    }
}

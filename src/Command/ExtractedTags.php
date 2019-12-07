<?php

namespace Pyro\Platform\Command;

class ExtractedTags
{
    /** @var string */
    protected $extractedHtml;

    /** @var string */
    protected $resultHtml;

    public function __construct(string $resultHtml, string $extractedHtml)
    {
        $this->extractedHtml = $extractedHtml;
        $this->resultHtml    = $resultHtml;
    }

    public function getExtractedHtml()
    {
        return $this->extractedHtml;
    }

    public function getResultHtml()
    {
        return $this->resultHtml;
    }

}

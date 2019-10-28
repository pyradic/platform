<?php

namespace Pyro\Platform\Entry\Command;

use Anomaly\Streams\Platform\Entry\Event\GatherParserData;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;
use Illuminate\Events\Dispatcher;

class AddTraitToEntryModel
{
    /** @var string */
    protected $namespace;

    /** @var string */
    protected $slug;

    /** @var string */
    protected $trait;

    public static $skip = [];

    public function __construct(string $namespace, string $slug, string $trait)
    {
        $this->namespace = $namespace;
        $this->slug      = $slug;
        $this->trait     = $trait;
    }

    public function handle(StreamRepositoryInterface $streams, Dispatcher $events)
    {
        $stream = $streams->findBySlugAndNamespace($this->slug, $this->namespace);
        if ( ! $stream) {
            throw new \RuntimeException("Could not find stream [{$this->slug}][{$this->namespace}");
        }
        $events->listen(GatherParserData::class, function (GatherParserData $event) use ($stream) {
            if ($event->getStream()->id !== $stream->id || in_array($stream->id, static::$skip, true)) {
                return;
            }
            $data = $event->getData();
            $text = $data->get('trashable', '');
            $text .= "\n use \\{$this->trait};";
            $data->put('trashable', $text);
        });
    }

}

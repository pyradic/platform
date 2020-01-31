<?php

namespace Anomaly\Streams\Platform\Support;

interface HydratorInterface
{
    public function extract(object $object): array;

    public function hydrate(array $data, object $object): void;
}

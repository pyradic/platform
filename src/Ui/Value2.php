<?php

namespace Pyro\Platform\Ui;

use Anomaly\Streams\Platform\Entry\Contract\EntryInterface;
use Anomaly\Streams\Platform\Model\EloquentModel;
use Anomaly\Streams\Platform\Support\Value;
use Illuminate\Contracts\Support\Arrayable;

class Value2 extends Value
{
    public function make($parameters, $entry, $term = 'entry', $payload = [])
    {
        $payload[$term] = $entry;

        /*
         * If a flat value was sent in
         * then convert it to an array.
         */
        if (!is_array($parameters)) {
            $parameters = [
                'value' => $parameters,
            ];
        }

        $value = array_get($parameters, 'value');

        /*
         * If the value is a view path then return a view.
         */
        if ($view = array_get($parameters, 'view')) {
            return view($view, ['value' => $value, $term => $entry])->render();
        }

        /*
         * If the value uses a template then parse it.
         */
        if ($template = array_get($parameters, 'template')) {
            return (string)$this->template->render($template, ['value' => $value, $term => $entry]);
        }

        /*
         * If the entry is an instance of EntryInterface
         * then try getting the field value from the entry.
         */
        if ($entry instanceof EntryInterface && $entry->getField($value)) {

            /* @var EntryInterface $relation */
            if ($entry->assignmentIsRelationship($value) && $relation = $entry->{camel_case($value)}) {
                if ($relation instanceof EloquentModel) {
                    $value = $relation->getTitle();
                }
            } else {
                $value = $entry->getFieldValue($value);
            }
        }

        /*
         * Decorate the entry object before
         * sending to decorate so that data_get()
         * can get into the presenter methods.
         */
        $payload[$term] = $entry = $this->decorator->decorate($entry);

        /*
         * If the value matches a dot notation
         * then parse it as a template.
         */
        if (is_string($value) && preg_match("/^{$term}.([a-zA-Z\\_]+)/", $value, $match)) {
            $value = \Pyro\Platform\Ui\Input::expression("{{ {$value} }}", $payload);
//            $value = (string)$this->template->render("{{ {$value}|raw }}", $payload);
        }

        $payload[$term] = $entry;

        /*
         * By default we can just pass the value through
         * the evaluator utility and be done with it.
         */
        $value = $this->evaluator->evaluate($value, $payload);

        /*
         * Lastly, prepare the entry to be
         * parsed into the string.
         */
        if ($entry instanceof Arrayable) {
            $entry = $entry->toArray();
        }

        /*
         * Parse the value with the entry.
         */
        if ($wrapper = array_get($parameters, 'wrapper')) {
            $value = $this->parser->parse(
                $wrapper,
                ['value' => $value, $term => $entry]
            );
        }

        /*
         * Parse the value with the value too.
         */
        if (is_string($value)) {
            $value = $this->parser->parse(
                $value,
                [
                    'value' => $value,
                    $term   => $entry,
                ]
            );
        }

        /*
         * If the value looks like a language
         * key then try translating it.
         */
        if (is_string($value) && str_is('*.*.*::*', $value)) {
            $value = trans($value);
        }

        /*
         * If the value looks like a render-able
         * string then render it.
         */
        if (is_string($value) && str_contains($value, ['{{', '{%'])) {
            $value = (string)$this->template->render($value, [$term => $entry]);
        }

        if (is_string($value) && array_get($parameters, 'is_safe') !== true) {
            $value = $this->purifier->purify($value);
        }

        return $value;
    }
}

<?php

namespace Pyradic\Platform\Command;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeCollection;

class GatherJavascriptData
{
    /** @var \Illuminate\Support\Collection */
    protected $data;
    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    public function __construct(Collection $data = null)
    {
        $this->data = $data ?? new Collection();
    }


    public function handle(Application $app)
    {
        $this->app = $app;
        $this->addFieldTypes($app[ 'field_type.collection' ]);
        $this->addConfig($app[ 'config' ]);
        return $this->data;
    }

    protected function addFieldTypes(FieldTypeCollection $fieldTypes)
    {
        $this->data[ 'field_types' ] = $fieldTypes->map(function (FieldType $fieldType) {

            return [
                'name'       => trans($fieldType->getName()),
                'attributes' => $fieldType->getAttributes(),
                'config'     => $fieldType->getConfig(),
                'class'      => $fieldType->getClass(),
            ];
        })->toArray();
    }

    protected function addConfig(Repository $config)
    {
        $only                   = [ 'app', 'streams::addons', 'streams::variables' ];
        $this->data[ 'config' ] = collect($only)->mapWithKeys(function ($value) use ($config) {
            return [ $value => $config->get($value) ];
        })->toArray();
    }
}

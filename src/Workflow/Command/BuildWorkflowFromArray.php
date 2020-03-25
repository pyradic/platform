<?php

namespace Pyro\Platform\Workflow\Command;

use Illuminate\Support\Collection;
use Pyro\Platform\Workflow\Workflow;

class BuildWorkflowFromArray
{
    /** @var string */
    protected $slug;
    /** @var Collection */
    protected $data;

    /**
     * BuildWorkflowFromArray constructor.
     *
     * @param string $slug
     * @param array  $data
     */
    public function __construct(string $slug, array $data)
    {
        $this->slug = $slug;
        $this->data = collect($data);
    }

    public function handle()
    {
        $data = $this->data;
        $className = $data->get('class', Workflow::class );
        /** @var Workflow $workflow */
        $workflow = new $className($this->slug);

        foreach ($data->get('places', []) as $place) {
            $workflow->places->add($place);
        }

        foreach ($data->get('transactions', []) as $transactionSlug => $transaction) {
            $transaction[ 'slug' ] = $transactionSlug;
            $workflow->transitions->add($transaction);
        }

        $workflow->resolve_place = $data[ 'resolve_place' ];
        $workflow->addon         = $data[ 'addon' ];
        $workflow->routing       = $data->get('routing', []);

        dispatch_now(new SetupWorkflowRouting($workflow));
        return $workflow;
    }
}

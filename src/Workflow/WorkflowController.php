<?php

namespace Pyro\Platform\Workflow;

use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkflowController extends AdminController
{
    public function transition(WorkflowManager $manager,Request $request)
    {
        if($request->has('base64')) {
            $slug = base64_decode(Str::ensureRight($request->get('workflow'), '=='));
        } else {
            $slug = $request->get('workflow');

        }
        $transition = $request->get('transition');
        if(!$manager->has($slug)){
            abort(404, 'Workflow does not exist');
        }
        $workflow = $manager->get($slug);
        $subject = $request->route($workflow->routing['subject']);
        $result = $workflow->handle($subject, $transition);

        if($result->isResponse()){
            return $result->response;
        }
    }
}

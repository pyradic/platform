<?php

namespace Pyro\Platform\Database\Seeders;

use Anomaly\Streams\Platform\Model\EloquentModel;
use Pyro\MenusModule\MenuModuleSeederHelper;

class MenuSeeder extends \Pyro\Platform\Database\Seeder
{
    /** @var \Pyro\MenusModule\MenuModuleSeederHelper */
    protected $helper;

    public function run()
    {
        $this->helper = resolve(MenuModuleSeederHelper::class);
//        $this->seedMenu('admin_header');
        $this->seedMenu('admin_footer');
    }

    protected function seedMenu($menu)
    {
        $helper = $this->helper;
        $helper->menu($menu)->truncate();
        $clients = $helper->label('Clients');

        $helper->module('Users', [ 'link' => 'anomaly.module.users:users' ], [ 'parent_id' => $clients->getId() ]);
        $helper->module('Roles', [ 'link' => 'anomaly.module.users:roles' ], [ 'parent_id' => $clients->getId() ]);


        $multi    = $helper->label('Multi Level');
        $children = $this->createMultiLinksChildren($multi);
        $children = $this->createMultiLinksChildren($children[ 3 ]);
        $children = $this->createMultiLinksChildren($children[ 1 ]);
        $children = $this->createMultiLinksChildren($children[ 3 ]);
        $children = $this->createMultiLinksChildren($children[ 2 ]);
        $children = $this->createMultiLinksChildren($children[ 2 ]);
        $children = $this->createMultiLinksChildren($children[ 2 ]);
    }


    protected function createMultiLinksChildren(EloquentModel $parent)
    {
        $helper = $this->helper;

        $children[] = $helper->label('First Label Item', [], [ 'parent_id' => $parent->getId() ]);
        $children[] = $helper->label('Second Label Item', [], [ 'parent_id' => $parent->getId() ]);

        $helper->divider(null, [], [ 'parent_id' => $parent->getId() ]);
        $helper->header('Header Type', [], [ 'parent_id' => $parent->getId() ]);
        $helper->divider(null, [], [ 'parent_id' => $parent->getId() ]);

        $children[] = $helper->label('Third Label Item', [], [ 'parent_id' => $parent->getId() ]);
        $children[] = $helper->label('Fourth Label Item', [], [ 'parent_id' => $parent->getId() ]);
        return $children;
    }
}

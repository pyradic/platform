<?php namespace Anomaly\Streams\Platform\Ui\ControlPanel\Component\Button\Guesser;

use Anomaly\Streams\Platform\Addon\Module\ModuleCollection;
use Anomaly\Streams\Platform\Ui\ControlPanel\ControlPanelBuilder;

/**
 * Class PermissionGuesser
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class PermissionGuesser
{

    /**
     * The module collection.
     *
     * @var ModuleCollection
     */
    protected $modules;

    /**
     * Create a new TitleGuesser instance.
     *
     * @param ModuleCollection $modules
     */
    public function __construct(ModuleCollection $modules)
    {
        $this->modules = $modules;
    }

    /**
     * Guess the shortcuts title.
     *
     * @param ControlPanelBuilder $builder
     */
    public function guess(ControlPanelBuilder $builder)
    {
        if ( ! $module = $this->modules->active()) {
            return;
        }

        $buttons = $builder->getButtons();

        $sec = $module->getSections();

        $section = $builder->getControlPanelActiveSection();

        foreach ($buttons as &$button) {

            // If permission is set then skip it.
            if (isset($button[ 'permission' ])) {
                continue;
            }
            /*
             * Try and guess the permission value.
             */
            switch (array_get($button, 'button')) {

                case 'new':
                    $button[ 'permission' ] = str_replace('*', 'write', $section->getPermission()); //$module->getNamespace($stream->getSlug() . '.write');
                    break;

                default:
                    $button[ 'permission' ] = $section->getPermission();
                    break;
            }
        }

        $builder->setButtons($buttons);
    }
}

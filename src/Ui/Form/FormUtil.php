<?php /** @noinspection NullPointerExceptionInspection */

namespace Pyro\Platform\Ui\Form;

use Anomaly\Streams\Platform\Ui\Form\FormBuilder;
use Illuminate\Support\Arr;
use Laradic\Support\Wrap;

class FormUtil
{
    public static function modifySectionData(FormBuilder $builder, \Closure $modifier)
    {
        $sections = $builder->getForm()->getSections();
        $data     = Wrap::dot($sections->all());
        $modifier($data);
        foreach ($data->toArray() as $key => $value) {
            $sections->put($key, $value);
        }
    }

    /**
     * @param \Anomaly\Streams\Platform\Ui\Form\FormBuilder $builder
     * @param string                                        $path  The dot notated path to the fields, eg: 'user.tabs.account.fields'
     * @param string|string[]                               $slugs The field(s) to add
     *
     * @return void
     */
    public static function addFieldToSection(FormBuilder $builder, string $path, $slugs)
    {
        $slugs    = Arr::wrap($slugs);
        $sections = $builder->getForm()->getSections();
        $data     = $sections->all();
        $fields   = Arr::get($data, $path, []);
        $fields   = array_merge($fields, $slugs);
        Arr::set($data, $path, $fields);
        foreach ($data as $key => $value) {
            $sections->put($key, $value);
        }
    }

    public static function addFieldToForm(FormBuilder $builder, $slugs)
    {
        $slugs = Arr::wrap($slugs);
        $tmp   = resolve(FormBuilder::class);
        $tmp->setFields($slugs)
            ->setRepository($builder->getRepository())
            ->setFormMode($builder->getFormMode())
            ->setModel($builder->getModel())
            ->setEntry($builder->getEntry())
            ->build();

        $builder->addFields($tmp->getFields());
        foreach ($slugs as $slug) {
            $builder->addFormField($tmp->getFormField($slug));
        }
    }
}

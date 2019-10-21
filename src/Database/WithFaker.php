<?php


namespace Pyro\Platform\Database;


use Faker\Factory;

trait WithFaker
{

    /**
     * The Faker instance.
     *
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * Setup up the Faker instance.
     *
     * @return void
     */
    protected function setUpFaker()
    {
        $this->faker = $this->makeFaker();
    }

    /**
     * Get the default Faker instance for a given locale.
     *
     * @param string|null $locale
     *
     * @return \Faker\Generator
     */
    protected function faker($locale = null)
    {
        return $locale === null ? $this->getFaker() : $this->makeFaker($locale);
    }

    private function getFaker()
    {
        if ($this->faker === null) {
            $this->setUpFaker();
        }
        return $this->faker;
    }

    private function makeFaker($locale = null)
    {
        return Factory::create($locale ?? (config('app.locale') === 'nl' ? 'nl_NL' : 'en_US'));
    }

}

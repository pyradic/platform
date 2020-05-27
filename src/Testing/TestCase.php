<?php

namespace Pyro\Platform\Testing;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Pyro\Platform\PlatformServiceProvider;
use Tests\CreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

//    public function testUnit()
//    {
//        $this->markTestSkipped();
//    }
    protected function setUp(): void
    {
        $this->app->register(PlatformServiceProvider::class);
    }

    public function testTrue()
    {
        $this->assertTrue(true, 'Always true');
    }
}

<?php

namespace Vukasinl\Modals\Tests\Feature;

use Orchestra\Testbench\TestCase;
use Vukasinl\Modals\ModalsServiceProvider;

/**
 * ModalsTest
 * @group group
 */
class ModalsTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [
            ModalsServiceProvider::class
        ];
    }

    /** @test */
    public function test()
    {
        
    }

}

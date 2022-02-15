<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        #Artisan::call('passport:install');
    }
    public function tearDown(): void
    {


        Artisan::call('migrate:fresh');
        parent::tearDown();
    }


}

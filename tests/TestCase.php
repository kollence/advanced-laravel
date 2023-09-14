<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected function signIn($user = null)
    {
        $user = $user ?: factoryCreate(\App\Models\User::class);

        $this->actingAs($user);

        return $this;
    }

}

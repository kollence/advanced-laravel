<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::enableForeignKeyConstraints();       
    }
    
    protected function signIn($user = null)
    {
        $user = $user ?: factoryCreate(\App\Models\User::class);

        $this->actingAs($user);

        return $this;
    }

    protected function signInConfirmedUser($user = null)
    {
        $user = $user ?: factoryCreate(\App\Models\User::class, ['confirmed_email' => true]);

        $this->actingAs($user);

        return $this;
    }

}

<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Support\Str;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('password'), // Replace with your desired password
        // ... other user fields
    ];
});

$factory->define(Thread::class, function (Faker $faker) {
    return [
        'user_id' => fn () => User::factory()->create()->id,
        'channel_id' => fn () => Channel::factory()->create()->id,
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        // ... other thread fields
    ];
});

$factory->define(Channel::class, function (Faker $faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'slug' => Str::slug($name)
    ];
});

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'thread_id' => fn () => Thread::factory()->create()->id,
        'user_id' => fn () => User::factory()->create()->id,
        'body' => $faker->sentence,
        // ... other comment fields
    ];
});
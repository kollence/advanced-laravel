<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        return [
            'user_id' => fn () => User::factory()->create()->id,
            'channel_id' => fn () => Channel::factory()->create()->id,
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(),
        ];
    }
}

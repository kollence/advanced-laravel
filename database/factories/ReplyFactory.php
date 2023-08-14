<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'thread_id' => fn () => \App\Models\Thread::factory()->create()->id,
            'user_id' => fn () => \App\Models\User::factory()->create()->id,
            'body' => fake()->paragraph(),
        ];
    }
}

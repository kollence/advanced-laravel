<?php

namespace Database\Factories;

use App\Models\User;
use App\Notifications\ThreadWasReplied;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Notifications\DatabaseNotification;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Illuminate\Notifications\DatabaseNotification>
 */
class DatabaseNotificationFactory extends Factory
{

    protected $model = \Illuminate\Notifications\DatabaseNotification::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        
        return [
            'id' => fake()->uuid(),
            'type' => ThreadWasReplied::class, // your notification class
            'notifiable_id' => fn () => auth()->id() ?: User::factory()->create()->id,
            'notifiable_type' => User::class, // the actual model type
            'data' => ['message' => fake()->sentence()],
        ];
    }
}

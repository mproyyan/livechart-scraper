<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalAccessToken>
 */
class PersonalAccessTokenFactory extends Factory
{
    public const DEFAULT_PLAIN_TEXT_TOKEN = 'DyhqWAtcVfZFjXbfqKmrtEK8X235SZ779vPnPgDx';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence,
            'token' => hash('sha256', static::DEFAULT_PLAIN_TEXT_TOKEN),
            'abilities' => ['*'],
            'expired_at' => Carbon::createFromInterface($this->faker->dateTimeBetween('today', '+1 month')),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'FirstName' => fake()->text(),
            'LastName' => fake()->text(),
            'UserName' => fake()->text(),
            'Email' => fake()->text(),
            'Password' => fake()->text(),
            'PasswordHash' => bcrypt(fake()->text()),
            'RoleId' => fake()->randomNumber(),
        ];
    }
}

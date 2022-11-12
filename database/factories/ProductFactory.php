<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word . ' - ' . $this->faker->unique()->randomNumber(6),
            'price' => $this->faker->unique()->randomFloat(2, 1, 250),
            'description' => $this->faker->unique()->realText,
            'category' => $this->faker->unique()->firstName,
            'image_url' => $this->faker->unique()->url,
        ];
    }
}

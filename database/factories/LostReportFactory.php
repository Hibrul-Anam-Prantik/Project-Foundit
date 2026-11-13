<?php

namespace Database\Factories;

use App\Models\LostReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LostReport>
 */
class LostReportFactory extends Factory
{
    protected $model = LostReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'item_name' => fake()->randomElement([
                'Black Lenovo USB-C Charger',
                'Blue Hydro Flask Bottle',
                'Casio Scientific Calculator fx-991EX',
                'BRACU Student ID Lanyard',
                'Leather Brown Wallet',
            ]),
            'category' => fake()->randomElement(array_keys(LostReport::DEFAULT_CATEGORIES)),
            'description' => fake()->paragraph(2),
            'lost_date' => fake()->dateTimeBetween('-14 days', 'now')->format('Y-m-d'),
            'location' => fake()->randomElement(LostReport::DEFAULT_LOCATIONS),
            'photo_path' => null,
            'status' => LostReport::STATUS_SEARCHING,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'legal_name' => $name . ' Company Limited',
            'slug' => fake()->unique()->slug(2),
            'registration_number' => null,
            'registered_at' => fake()->date(),
            'province' => fake()->city(),
            'business_type' => 'Information technology services',
            'description' => fake()->paragraph(),
            'website_url' => fake()->url(),
            'published' => true,
        ];
    }
}

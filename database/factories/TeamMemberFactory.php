<?php

namespace Database\Factories;

use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TeamMember>
 */
class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'position' => fake()->jobTitle(),
            'bio' => fake()->paragraph(),
            'profile_image' => null,
            'email' => fake()->safeEmail(),
            'linkedin_url' => 'https://www.linkedin.com/in/' . fake()->userName(),
            'status' => 'active',
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Student;
use App\Enums\CitiesEnum;
use App\Enums\GenderEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'birth_date' => $this->faker->date,
            'notes' => $this->faker->optional()->paragraph,
            'address' => $this->faker->address,
            'city' => $this->faker->randomElement(array_column(CitiesEnum::cases(), 'value')),
            'full_name' => $this->faker->name,
            'gender' => $this->faker->randomElement(array_column(GenderEnum::cases(), 'value')),
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'phone_number' => $this->faker->unique()->phoneNumber,
            'is_active' => $this->faker->boolean,
            'email_verified_at' => $this->faker->optional()->dateTimeThisYear,
            'country_code' => $this->faker->countryCode,
            'remember_token' => Str::random(10),
        ];
    }
}

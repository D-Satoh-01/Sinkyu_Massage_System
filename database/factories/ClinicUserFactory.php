<?php
// database/factories/ClinicUserModelFactory.php


namespace Database\Factories;

use App\Models\ClinicUserModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClinicUserModel>
 */
class ClinicUserModelFactory extends Factory
{
    protected $model = ClinicUserModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clinic_user_name' => $this->faker->name(),
            'furigana' => $this->faker->lastName() . ' ' . $this->faker->firstName(),
            'birthday' => $this->faker->date(),
            'age' => $this->faker->numberBetween(18, 80),
            'gender_id' => $this->faker->numberBetween(1, 2),
            'postal_code' => $this->faker->postcode(),
            'address_1' => $this->faker->state(),
            'address_2' => $this->faker->city(),
            'address_3' => $this->faker->streetAddress(),
            'phone' => $this->faker->phoneNumber(),
            'cell_phone' => $this->faker->phoneNumber(),
            'fax' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'housecall_distance' => $this->faker->numberBetween(1, 50),
            'housecall_additional_distance' => $this->faker->numberBetween(0, 20),
            'is_redeemed' => $this->faker->boolean(),
            'application_count' => $this->faker->numberBetween(0, 100),
            'note' => $this->faker->text(200),
        ];
    }
}

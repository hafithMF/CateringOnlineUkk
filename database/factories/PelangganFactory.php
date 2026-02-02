<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PelangganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name_pelanggan' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password123'),
            'tgl_lahir' => $this->faker->date(),
            'telepon' => $this->faker->phoneNumber,
            'alamat1' => $this->faker->address,
            'alamat2' => $this->faker->secondaryAddress,
            'alamat3' => $this->faker->optional()->address,
            'kartu_id' => $this->faker->optional()->uuid,
            'foto' => null,
        ];
    }
}
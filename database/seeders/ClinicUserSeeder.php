<?php
// database/seeders/ClinicUserSeeder.php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClinicUserModel;

class ClinicUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClinicUserModel::factory()->count(150)->create();
    }
}

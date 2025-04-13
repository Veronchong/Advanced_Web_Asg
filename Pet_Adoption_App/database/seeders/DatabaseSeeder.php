<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pet;
use App\Models\AdoptionRequest;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            PetsTableSeeder::class,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create staff user
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@example.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        // Create regular users
        $users = User::factory(5)->create(['role' => 'user']);

        // Create pets
        $pets = Pet::factory(20)->create([
            'user_id' => $staff->id,
        ]);

        // Create adoption requests
        foreach ($users as $user) {
            $randomPets = $pets->random(rand(1, 3));
            foreach ($randomPets as $pet) {
                AdoptionRequest::factory()->create([
                    'pet_id' => $pet->id,
                    'user_id' => $user->id,
                    'status' => rand(0, 1) ? 'pending' : (rand(0, 1) ? 'approved' : 'rejected'),
                ]);
            }
        }
    }
}
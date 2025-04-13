<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PetsTableSeeder extends Seeder
{
    public function run()
    {
        // Get the first user to associate with pets
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Pet::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $staffUser = User::where('email', 'staff@example.com')->firstOrFail();

        $pets = [
            [
                'name' => 'Buddy',
                'species' => 'dog',
                'breed' => 'Golden Retriever',
                'age' => 3,
                'gender' => 'male',
                'description' => 'Friendly and playful golden retriever. Loves fetch and long walks.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'pet_images/golden-retriever.jpg'
            ],
            [
                'name' => 'Luna',
                'species' => 'cat',
                'breed' => 'Siamese',
                'age' => 2,
                'gender' => 'female',
                'description' => 'Gentle and affectionate Siamese cat. Gets along with other pets.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'pet_images/siamese-cat.jpg'
            ],
            [
                'name' => 'Max',
                'species' => 'dog',
                'breed' => 'Beagle',
                'age' => 5,
                'gender' => 'male',
                'description' => 'Curious and energetic beagle. Great with kids and other dogs.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'pet_images/beagle.jpg'
            ],
            [
                'name' => 'Bella',
                'species' => 'cat',
                'breed' => 'Persian',
                'age' => 4,
                'gender' => 'female',
                'description' => 'Calm and fluffy Persian cat. Prefers a quiet environment.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'pet_images/persian-cat.jpg'
            ],
            [
                'name' => 'Charlie',
                'species' => 'bird',
                'breed' => 'Parakeet',
                'age' => 1,
                'gender' => 'male',
                'description' => 'Colorful and vocal parakeet. Can mimic some sounds.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now(),
                'updated_at' => now(),
                'image' => 'pet_images/parakeet-bird.jpg'
            ],
            [
                'name' => 'Rocky',
                'species' => 'dog',
                'breed' => 'Bulldog',
                'age' => 4,
                'gender' => 'male',
                'description' => 'Gentle giant with a heart of gold. Lazy but loving.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(2),
                'image' => 'pet_images/bulldog.jpg',
            ],
            [
                'name' => 'Daisy',
                'species' => 'dog', 
                'breed' => 'Dachshund',
                'age' => 2,
                'gender' => 'female',
                'description' => 'Playful little sausage dog with lots of energy.',
                'status' => 'pending',
                'user_id' => $staffUser->id,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1),
                'image' => 'pet_images/dachshund.jpg',
            ],
            [
                'name' => 'Oliver',
                'species' => 'cat',
                'breed' => 'Tabby',
                'age' => 1,
                'gender' => 'male',
                'description' => 'Young and mischievous tabby cat. Loves toys and climbing.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now()->subDays(3),
                'updated_at' => now(),
                'image' => 'pet_images/tabby-cat.jpg',
            ],
            [
                'name' => 'Misty',
                'species' => 'cat',
                'breed' => 'Ragdoll',
                'age' => 3,
                'gender' => 'female',
                'description' => 'Fluffy ragdoll who goes limp when you hold her.',
                'status' => 'adopted',
                'user_id' => $staffUser->id,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(15),
                'image' => 'pet_images/ragdoll-cat.jpg',
            ],
            [
                'name' => 'Peanut',
                'species' => 'other',
                'breed' => 'Netherland Dwarf',
                'age' => 1,
                'gender' => 'female',
                'description' => 'Tiny bunny with lots of personality. Litter trained.',
                'status' => 'available',
                'user_id' => $staffUser->id,
                'created_at' => now()->subDays(7),
                'updated_at' => now(),
                'image' => 'pet_images/netherland-dwarf.jpg',
            ],
        ];

        foreach ($pets as $pet) {
            // Only set image if file exists
            if (isset($pet['image']) && 
                file_exists(storage_path('app/public/'.$pet['image']))) {
                Pet::updateOrCreate(
                    ['name' => $pet['name']],
                    $pet
                );
            } else {
                // Create without image if file doesn't exist
                unset($pet['image']);
                Pet::updateOrCreate(
                    ['name' => $pet['name']],
                    $pet
                );
            }
        }
    }
}
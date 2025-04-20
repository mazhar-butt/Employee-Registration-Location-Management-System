<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    //public function run(): void
   // {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
   // }
   public function run()
   {
       // Create countries first
       Country::factory(10)->create()->each(function ($country) {
           // For each country, create 5 states
           State::factory(5)->create([
               'country_id' => $country->id
           ])->each(function ($state) {
               // For each state, create 4 cities
               City::factory(4)->create([
                   'state_id' => $state->id,
                   'country_id' => $state->country_id
               ]);
           });
       });
   }
}

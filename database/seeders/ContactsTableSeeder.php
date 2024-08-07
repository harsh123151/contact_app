<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $groups = Group::factory()->count(5)->create();

         Contact::factory()->count(20)->create()->each(function ($contact) use ($groups) {
             $contact->groups()->attach($groups->random(rand(1, 3))->pluck('id')->toArray());
         });
    }
}

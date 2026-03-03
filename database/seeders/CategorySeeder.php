<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categories = [
        'Discounts', 
        'Eyes', 
        'Joint and Bone Health', 
        'Heart and Cardiovascular Health', 
        'Sleep and Relaxation', 
        'Immune System'
    ];

    foreach ($categories as $cat) {
        \App\Models\Category::create([
            'name' => $cat,
            'slug' => \Illuminate\Support\Str::slug($cat) 
        ]);
    }
}
}

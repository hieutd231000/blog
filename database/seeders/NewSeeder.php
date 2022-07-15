<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Admin record
        for($i=0; $i<5; $i++) {
            DB::table("news")->insert([
                'title' => 'Pigma Design',
                'description' => 'Pigma is an all-in-one design tool',
                'detail' => 'Pigma is an all-in-one design tool Pigma is an all-in-one design tool Pigma is an all-in-one design tool Pigma is an all-in-one design tool',
                'image' => "kk",
                'thumbnail' => "kk",
                'state' => 0,
            ]);
        }
    }
}

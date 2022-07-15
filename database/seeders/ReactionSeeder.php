<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $icons = array("angry.png", "care.png", "haha.png", "like.png", "love.png", "sad.png", "wow.png");
        for($i = 0; $i < 7; $i++) {
            DB::table("reactions")->insert([
                'icon' => $icons[$i],
            ]);
        }
    }
}

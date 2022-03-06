<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'test1',
                'email' => 'yokoi4522-test1@gmail.com',
                'password' => bcrypt('test'),
            ],
            [
                'name' => 'test2',
                'email' => 'yokoi4522-test2@gmail.com',
                'password' => bcrypt('test'),
            ],
        ]);
    }
}

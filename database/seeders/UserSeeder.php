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
        DB::table('branches')->insert([
            'branch_code' => 'JLM',
            'branch_name' => 'Muchtar Raya',
            'phone_number' => '0812347584756',
            'address' => 'jl muchtar raya',
            'social_media' => 'instagram',
            'created_by' => 1,
            'updated_by' => 2,
            'created_at' => '2022-05-22',
            'updated_at' => '2022-05-22',
        ]);

        DB::table('users')->insert([
            'username' => 'ahmed',
            'fullname' => 'Ahmad Mukhtar',
            'email' => 'amx23baradja@gmail.com',
            'password' => bcrypt('adminadmin'),
            'role' => 'admin',
            'branch_id' => 1,
        ]);
    }
}

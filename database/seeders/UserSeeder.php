<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            'name' => 'Zachroy',
            'email' => 'zachroy@um.edu.my',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ];

        DB::table('users')
            ->insert($data);
    }

}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'ronan@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('ut99vt4cb4gt8'), // password
            'remember_token' => Str::random(10)
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            array(
                'name'=>'Admin',
                'email'=>'admin@gmail.com',
                'phone' => '0856068959',
                'password'=>Hash::make('admin123'),
                'role'=>'admin',
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
            )
        );

        DB::table('users')->insert($data);
    }
}

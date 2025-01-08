<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            array(
                'name'=>'Khảo bài',
            ),
            array(
                'name'=> 'Toán',
            ),
            array(
                'name'=>'Lý',
            ),
            array(
                'name'=>'Hóa',
            ),
            array(
                'name'=>'Văn',
            ),
            array(
                'name'=>'Tiếng Việt',
            ),
            array(
                'name'=>'Tiếng Anh',
            ),
            array(
                'name'=>'Sinh',
            ),
            array(
                'name'=>'Sử',
            ),
            array(
                'name'=>'Địa',
            ),
            array(
                'name'=>'Tin Học',
            ),
            array(
                'name'=>'GDCD',
            ),
            array(
                'name'=>'Khoa học tự nhiên',
            ),
            array(
                'name'=>'Khoa học xã hội',
            ),
            array(
                'name'=>'Rèn chữ',
            ),
            array(
                'name'=>'Tiếng Anh giao tiếp',
            ),
            array(
                'name'=>'Tiếng Pháp',
            ),
            array(
                'name'=>'Tiếng Hàn',
            ),
            array(
                'name'=>'Tiếng Hoa',
            ),
            array(
                'name'=>'Tiếng Nhật',
            ),
        );

        DB::table('subjects')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TuitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            array(
                'range'=> 'Từ 50.000đ-100.000đ/buổi',
            ),
            array(
                'range'=> 'Từ 100.000đ-150.000đ/buổi',
            ),
            array(
                'range'=> 'Từ 150.000đ-200.000đ/buổi',
            ),
            array(
                'range'=> 'Từ 200.000đ-250.000đ/buổi',
            ),
            array(
                'range'=> 'Từ 250.000đ-300.000đ/buổi',
            ),
            array(
                'range'=> 'Thỏa thuận',
            ),
        );

        DB::table('tuitions')->insert($data);
    }
}

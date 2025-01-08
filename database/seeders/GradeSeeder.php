<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            array(
                'name'=>'Mầm non',
            ),
            array(
                'name'=>'Lớp 1',
            ),
            array(
                'name'=>'Lớp 2',
            ),
            array(
                'name'=>'Lớp 3',
            ),
            array(
                'name'=>'Lớp 4',
            ),
            array(
                'name'=>'Lớp 5',
            ),
            array(
                'name'=>'Lớp 6',
            ),
            array(
                'name'=>'Lớp 7',
            ),
            array(
                'name'=>'Lớp 8',
            ),
            array(
                'name'=>'Lớp 9',
            ),
            array(
                'name'=>'Lớp 10',
            ),
            array(
                'name'=>'Lớp 11',
            ),
            array(
                'name'=>'Lớp 12',
            ),
            array(
                'name'=>'Ôn thi vào 10 ',
            ),
            array(
                'name'=>'Ôn thi vào 10 (Chuyên)',
            ),
            array(
                'name'=>'Ôn thi Đại học',
            ),
            array(
                'name'=>'Lớp khác',
            ),
        );

        DB::table('grades')->insert($data);
    }
}

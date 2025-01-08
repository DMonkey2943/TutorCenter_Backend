<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            array(
                'name'=> 'Thạc sĩ',
            ),
            array(
                'name'=>'Cao học',
            ),
            array(
                'name'=>'Đại học',
            ),
            array(
                'name'=> 'Cao đẳng',
            ),
            array(
                'name'=>'Sinh viên Đại học',
            ),
            array(
                'name'=> 'Sinh viên Cao đẳng',
            ),
            array(
                'name'=> 'Trung cấp',
            ),
        );

        DB::table('levels')->insert($data);
    }
}

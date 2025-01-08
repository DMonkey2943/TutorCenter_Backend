<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $data=array(
            array(
                'name'=> 'Quận Bình Thủy',
            ),
            array(
                'name'=>'Quận Cái Răng',
            ),
            array(
                'name'=>'Huyện Cờ Đỏ',
            ),
            array(
                'name'=> 'Quận Ninh Kiều',
            ),
            array(
                'name'=>'Quận Ô Môn',
            ),
            array(
                'name'=>'Huyện Phong Điền',
            ),
            array(
                'name'=> 'Huyện Thới Lai',
            ),
            array(
                'name'=>'Quận Thốt Nốt',
            ),
            array(
                'name'=>'Huyện Vĩnh Thạnh',
            ),
        );

        DB::table('districts')->insert($data);
    }
}

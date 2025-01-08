<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=array(
            // Binh Thuy
            array(
                'name'=> 'Phường An Thới',
                'district_id'=>1,
            ),
            array(
                'name'=>'Phường Bình Thủy',
                'district_id'=>1,
            ),
            array(
                'name'=>'Phường Bùi Hữu Nghĩa',
                'district_id'=>1,
            ),
            array(
                'name'=> 'Phường Long Hòa',
                'district_id'=>1,
            ),
            array(
                'name'=>'Phường Long Tuyền',
                'district_id'=>1,
            ),
            array(
                'name'=>'Phường Thới An Đông',
                'district_id'=>1,
            ),
            array(
                'name'=> 'Phường Trà An',
                'district_id'=>1,
            ),
            array(
                'name'=>'Phường Trà Nóc',
                'district_id'=>1,
            ),
            // Cai Rang
            array(
                'name'=>'Phường Ba Láng',
                'district_id'=>2,
            ),
            array(
                'name'=> 'Phường Hưng Phú',
                'district_id'=>2,
            ),
            array(
                'name'=>'Phường Hưng Thạnh',
                'district_id'=>2,
            ),
            array(
                'name'=>'Phường Lê Bình',
                'district_id'=>2,
            ),
            array(
                'name'=> 'Phường Phú Thứ',
                'district_id'=>2,
            ),
            array(
                'name'=>'Phường Tân Phú',
                'district_id'=>2,
            ),
            array(
                'name'=>'Phường Thường Thạnh',
                'district_id'=>2,
            ),
            // Co Do
            array(
                'name'=> 'Thị trấn Cờ Đỏ',
                'district_id'=>3,
            ),
            array(
                'name'=>'Xã Thạnh Phú',
                'district_id'=>3,
            ),
            array(
                'name'=> 'Xã Thới Hưng',
                'district_id'=>3,
            ),
            array(
                'name'=>'Xã Thới Xuân',
                'district_id'=>3,
            ),
            array(
                'name'=>'Xã Thới Đông',
                'district_id'=>3,
            ),
            array(
                'name'=> 'Xã Trung An',
                'district_id'=>3,
            ),
            array(
                'name'=>'Xã Trung Hưng',
                'district_id'=>3,
            ),
            array(
                'name'=> 'Xã Trung Thạnh',
                'district_id'=>3,
            ),
            array(
                'name'=>'Xã Đông Hiệp',
                'district_id'=>3,
            ),
            array(
                'name'=>'Xã Đông Thắng',
                'district_id'=>3,
            ),
            // Ninh Kieu
            array(
                'name'=>'Phường An Bình',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường An Cư',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường An Hòa',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường An Khánh',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường An Nghiệp',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường An Phú',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường Cái Khế',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường Hưng Lợi',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường Tân An',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường Thới Bình',
                'district_id'=>4,
            ),
            array(
                'name'=>'Phường Xuân Khánh',
                'district_id'=>4,
            ),
            // O Mon
            array(
                'name'=>'Phường Châu Văn Liêm',
                'district_id'=>5,
            ),
            array(
                'name'=>'Phường Long Hưng',
                'district_id'=>5,
            ),
            array(
                'name'=>'Phường Phước Thới',
                'district_id'=>5,
            ),
            array(
                'name'=>'Phường Thới An',
                'district_id'=>5,
            ),
            array(
                'name'=>'Phường Thới Hòa',
                'district_id'=>5,
            ),
            array(
                'name'=>'Phường Thới Long',
                'district_id'=>5,
            ),
            array(
                'name'=>'Phường Trường Lạc',
                'district_id'=>5,
            ),
            // Phong Dien
            array(
                'name'=>'Xã Giai Xuân',
                'district_id'=>6,
            ),
            array(
                'name'=>'Xã Mỹ Khánh',
                'district_id'=>6,
            ),
            array(
                'name'=>'Xã Nhơn Ái',
                'district_id'=>6,
            ),
            array(
                'name'=>'Xã Nhơn Nghĩa',
                'district_id'=>6,
            ),
            array(
                'name'=>'Thị trấn Phong Điền',
                'district_id'=>6,
            ),
            array(
                'name'=>'Xã Tân Thới',
                'district_id'=>6,
            ),
            array(
                'name'=>'Xã Trường Long',
                'district_id'=>6,
            ),
            // Thoi Lai
            array(
                'name'=>'Xã Tân Thạnh',
                'district_id'=>7,
            ),
            array(
                'name'=>'Thị trấn Thới Lai',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Thới Tân',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Thới Thạnh',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Trường Thắng',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Trường Thành',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Trường Xuân',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Trường Xuân A',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Trường Xuân B',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Xuân Thắng',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Định Môn',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Đông Bình',
                'district_id'=>7,
            ),
            array(
                'name'=>'Xã Đông Thuận',
                'district_id'=>7,
            ),
            // Thot Not
            array(
                'name'=>'Phường Tân Hưng',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Tân Lộc',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Thạnh Hoà',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Thới Thuận',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Thốt Nốt',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Thuận An',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Thuận Hưng',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Trung Kiên',
                'district_id'=>8,
            ),
            array(
                'name'=>'Phường Trung Nhứt',
                'district_id'=>8,
            ),
            // Vinh Thanh
            array(
                'name'=>'Thị trấn Thanh An',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Thạnh An',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Thạnh Lộc',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Thạnh Lợi',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Thạnh Mỹ',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Thạnh Qưới',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Thạnh Thắng',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Thạnh Tiến',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Vĩnh Bình',
                'district_id'=>9,
            ),
            array(
                'name'=>'Thị trấn Vĩnh Thạnh',
                'district_id'=>9,
            ),
            array(
                'name'=>'Xã Vĩnh Trinh',
                'district_id'=>9,
            ),

        );

        DB::table('wards')->insert($data);
    }
}

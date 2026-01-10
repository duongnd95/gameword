<?php

namespace Modules\Booking\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BookingDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $datenow = date( 'Y-m-d H:i:s' );

        \DB::table('payment_methods')->insert([
            ['id' => 1,'name'=>'Thanh Toán Tiền Mặt'],
            ['id' => 2,'name'=>'Thanh Toán PayPal'],
            ['id' => 3,'name'=>'Thanh Toán VNPay']
        ]);

        \DB::table('approves')->insert([
            [ 'id' => 1, 'name' => 'Chờ xác nhận', 'number' => '1', 'color' => '#ffa726', 'created_at' => $datenow, 'updated_at' => $datenow ],
            [ 'id' => 2, 'name' => 'Chờ lấy hàng', 'number' => '2', 'color' => '#ffa726', 'created_at' => $datenow, 'updated_at' => $datenow ],
            [ 'id' => 3, 'name' => 'Đang giao', 'number' => '3', 'color' => '#55acee', 'created_at' => $datenow, 'updated_at' => $datenow ],
            [ 'id' => 4, 'name' => 'Đặt lại', 'number' => '4', 'color' => '#26c6da', 'created_at' => $datenow, 'updated_at' => $datenow ],
            [ 'id' => 5, 'name' => 'Đã giao', 'number' => '5', 'color' => '#66bb6a', 'created_at' => $datenow, 'updated_at' => $datenow ],
            [ 'id' => 6, 'name' => 'Đã hủy', 'number' => '6', 'color' => '#f44336', 'created_at' => $datenow, 'updated_at' => $datenow ],
            [ 'id' => 7, 'name' => 'Trả hàng', 'number' => '7', 'color' => '#ffa726', 'created_at' => $datenow, 'updated_at' => $datenow ],
            [ 'id' => 8, 'name' => 'Đặt hàng nhanh', 'number' => '8', 'color' => '#0000a0', 'created_at' => $datenow, 'updated_at' => $datenow ],
        ]);

        // $this->call("OthersTableSeeder");
    }
}

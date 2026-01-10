<?php

return [
    'name' => 'Product',
    /**
     * Cấu hình Booking cho module nếu có sử dụng
     */
    'booking' => [
        'active' => true,//false nếu ko sử dụng đơn hàng
        'model' => 'Product',//default: module name - tên bảng dịch vụ, mặc đinh lấy theo tên module
        'column_name' => 'name',//tên trường cần hiển thị ở bảng dịch vụ, default: name
        'multiple' => false,//true: cho phép đặt nhiều dịch vụ 1 lần; false: chỉ đặt 1 dịch vu 1 lần.
        'check_quantity' => true,//true: tính theo số lượng sản phẩm
        'price' => 'getPriceBooking',
    ],
];

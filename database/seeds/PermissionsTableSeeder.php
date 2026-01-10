<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arPermissions = [
            "1" => ["HomeController@index", "Trang chủ"],

            "2" => ["UsersController@index", "Tài khoản người dùng"],
            "3" => ["UsersController@show", "Tài khoản người dùng"],
            "4" => ["UsersController@store", "Tài khoản người dùng"],
            "5" => ["UsersController@update", "Tài khoản người dùng"],
            "6" => ["UsersController@destroy", "Tài khoản người dùng"],
            "7" => ["UsersController@active", "Tài khoản người dùng"],
            //Trường hợp cho phép người dùng sửa, thì cho phép sửa profile của người dùng đó
            "8" => ["UsersController@postProfile", "Tài khoản người dùng"],

            "9" => ["RolesController@index", "Quản lý Vai trò"],
            "10" => ["RolesController@show", "Quản lý Vai trò"],
            "11" => ["RolesController@store", "Quản lý Vai trò"],
            "12" => ["RolesController@update", "Quản lý Vai trò"],
            "13" => ["RolesController@destroy", "Quản lý Vai trò"],
            "14" => ["RolesController@active", "Quản lý Vai trò"],

            "15" => ["SettingController", "Cấu hình công ty"],

            //Quyền module News
            "16" => ["CategoryController@index", "Danh mục tin tức"],
            "17" => ["CategoryController@show", "Danh mục tin tức"],
            "18" => ["CategoryController@store", "Danh mục tin tức"],
            "19" => ["CategoryController@update", "Danh mục tin tức"],
            "20" => ["CategoryController@destroy", "Danh mục tin tức"],
            "21" => ["CategoryController@active", "Danh mục tin tức"],

            "22" => ["NewsController@index", "Tin tức"],
            "23" => ["NewsController@show", "Tin tức"],
            "24" => ["NewsController@store", "Tin tức"],
            "25" => ["NewsController@update", "Tin tức"],
            "26" => ["NewsController@destroy", "Tin tức"],
            "27" => ["NewsController@active", "Tin tức"],

            //Quyền module Theme
            "28" => ["SysMenuController@index", "Hệ thống menu"],
            "29" => ["SysMenuController@show", "Hệ thống menu"],
            "30" => ["SysMenuController@store", "Hệ thống menu"],
            "31" => ["SysMenuController@update", "Hệ thống menu"],
            "32" => ["SysMenuController@destroy", "Hệ thống menu"],
            "33" => ["SysMenuController@active", "Hệ thống menu"],

            "34" => ["PageController@index", "Trang"],
            "35" => ["PageController@show", "Trang"],
            "36" => ["PageController@store", "Trang"],
            "37" => ["PageController@update", "Trang"],
            "38" => ["PageController@destroy", "Trang"],
            "39" => ["PageController@active", "Trang"],

            "40" => ["SliderController@index", "Slider"],
            "41" => ["SliderController@show", "Slider"],
            "42" => ["SliderController@store", "Slider"],
            "43" => ["SliderController@update", "Slider"],
            "44" => ["SliderController@destroy", "Slider"],
            "45" => ["SliderController@active", "Slider"],

        ];

      //ADD PERMISSIONS - Thêm các quyền
        DB::table('permissions')->delete(); //empty permission
        $addPermissions = [];
        foreach ($arPermissions as $name => $label) {
            $addPermissions[] = [
                'id' => $name,
                'name' => $label[0],
                'label' => $label[1],
            ];
        }
        \DB::table('permissions')->insert($addPermissions);

        //ADD ROLE - Them vai tro
        DB::table('roles')->delete();//empty permission
        $datenow = date('Y-m-d H:i:s');
        $role = [
            ['id' => 1, 'name' => 'admin', 'label' => 'Admin', 'created_at' => $datenow, 'updated_at' => $datenow],
            ['id' => 2, 'name' => 'ctv', 'label' => 'CTV', 'created_at' => $datenow, 'updated_at' => $datenow],

        ];
        $addRoles = [];
        foreach ($role as $key => $label) {
            $addRoles[] = [
                'id' => $label['id'],
                'name' => $label['name'],
                'label' => $label['label'],
                'created_at' => $datenow,
                'updated_at' => $datenow,
            ];
        }
        //KIỂM TRA VÀ THÊM CÁC VAI TRÒ TRUYỀN VÀO NẾU CÓ
        \DB::table('roles')->insert($addRoles);

        //BỔ SUNG ID QUYỀN NẾU CÓ
        //Full quyền Admin công ty
        $persAdmin = \App\Permission::pluck('id');

        //Quyền giáo viên
        $persVendor = [
            1, 22, 23, 24, 25
        ];

    
        //Gán quyền vào Vai trò Admin
        $rolePerAdminCompany = \App\Role::findOrFail(1);
        $rolePerAdminCompany->permissions()->sync($persAdmin);

        //Gán quyền vào Vai trò User
        $rolePerAgentEmployee = \App\Role::findOrFail(2);
        $rolePerAgentEmployee->permissions()->sync($persVendor);

        //Set tài khoản ID=1 và ID=2 là Admin
        $roleAdmin = \App\User::find(7);
        $roleAdmin->roles()->sync([1]);
    }
}

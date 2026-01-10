<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use App\Traits\Authorizable;
use Illuminate\Http\Request;
use Session;

class RolesController extends Controller
{
    use Authorizable;
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $roles = Role::where('name', 'LIKE', "%$keyword%")->orWhere('label', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $roles = Role::paginate($perPage);
        }

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $permissions = Permission::select('id', 'name', 'label')->orderBy('id', 'ASC')->get();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'terms.*' => 'accepted'
        ]);

        $role = Role::create($request->all());
        if(!empty($request->permissions)) {
            foreach ( $request->permissions as $permission_name ) {
                // kiem tra neu la report thi save cac permission cua report
                if (strcmp($permission_name, "ReportsController@index")==0)
                {
                    $permission = Permission::whereName( "ReportsController@numberBookingByDate" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@numberBookingByMonth" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@numberBookingByYear" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@peopleBookingByDate" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@peopleBookingByMonth" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@peopleBookingByYear" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@financeBookingByDate" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@financeBookingByMonth" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@financeBookingByYear" )->first();
                    $role->givePermissionTo( $permission );
                }
                else
                {
                    // neu la cho phep them tour thi luu quyen copy tour
//                    if (strcmp($permission_name, "ToursController@store")==0)
//                    {
//                        $permission = Permission::whereName( "ToursController@getCopyTour" )->first();
//                        $role->givePermissionTo( $permission );
//                    }
                    // neu la cho phep sửa người dùng thì lưu quyền cho phép sửa profile
                    if (strcmp($permission_name, "UsersController@update")==0)
                    {
                        $permission = Permission::whereName( "UsersController@postProfile" )->first();
                        $role->givePermissionTo( $permission );
                    }
                    // them module @show vao khi co quyen xem tru HomeController
                    if (strcmp($permission_name, "HomeController@index")!=0 && in_array("index", explode("@", $permission_name)))
                    {
                        $tmp = explode("@", $permission_name)[0];
                        $permission = Permission::whereName( $tmp."@show" )->first();
                        $role->givePermissionTo( $permission );
                    }
                    $permission = Permission::whereName( $permission_name )->first();
                    $role->givePermissionTo( $permission );
                }
            }
        }

        toastr()->success(trans('message.role.created_success'));

        return redirect('admin/roles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::select('id', 'name', 'label')->get();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'terms.*' => 'accepted'
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        $role->permissions()->detach();
        if(!empty($request->permissions)) {
            foreach ( $request->permissions as $permission_name ) {
                // kiem tra neu la report thi save cac permission cua report
                if (strcmp($permission_name, "ReportsController@index")==0)
                {
                    $permission = Permission::whereName( "ReportsController@numberBookingByDate" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@numberBookingByMonth" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@numberBookingByYear" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@peopleBookingByDate" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@peopleBookingByMonth" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@peopleBookingByYear" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@financeBookingByDate" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@financeBookingByMonth" )->first();
                    $role->givePermissionTo( $permission );
                    $permission = Permission::whereName( "ReportsController@financeBookingByYear" )->first();
                    $role->givePermissionTo( $permission );
                }
                else
                {
                    // neu la cho phep them tour thi luu quyen copy tour
//                    if (strcmp($permission_name, "ToursController@store")==0)
//                    {
//                        $permission = Permission::whereName( "ToursController@getCopyTour" )->first();
//                        $role->givePermissionTo( $permission );
//                    }
                    // neu la cho phep sửa người dùng thì lưu quyền cho phép sửa profile
                    if (strcmp($permission_name, "UsersController@update")==0)
                    {
                        $permission = Permission::whereName( "UsersController@postProfile" )->first();
                        $role->givePermissionTo( $permission );
                    }
                    if (strcmp($permission_name, "HomeController@index")!=0 &&
                        in_array("index", explode("@", $permission_name)))
                    {
                        $tmp = explode("@", $permission_name)[0];
                        $permission = Permission::whereName( $tmp."@show" )->first();
                        $role->givePermissionTo( $permission );
                    }
                    $permission = Permission::whereName( $permission_name )->first();
                    $role->givePermissionTo( $permission );
                }
            }
        }

        toastr()->success(trans('message.role.updated_success'));

        return redirect('admin/roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        Role::destroy($id);

        toastr()->success(trans('message.role.deleted_success'));

        return redirect('admin/roles');
    }
}

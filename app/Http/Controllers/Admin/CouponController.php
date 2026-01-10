<?php

namespace App\Http\Controllers\Admin;

use App\Coupon;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CouponController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
         $keyword = $request->get('search');
         $perPage = config('settings.perpage');
 
         $coupons = Coupon::sortable();
 
         if (!empty($keyword)){
             $coupons = $coupons->where('title','LIKE',"%$keyword%");
         }
 
         $coupons = $coupons->paginate($perPage);
 
         return view('admin.coupons.index', compact('coupons'));
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         //Lấy list áp dụng KM
         $apply_target = Coupon::getListApplyTarget();
         //Lấy list loại KM
         $type = Coupon::getListType();
         //Lấy list user_id
         $user = User::with('profile')->pluck('name','id');
         return view('admin.coupons.create', compact('apply_target','type', 'user'));
     }
 
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
         $this->validate($request, [
             'name' => 'required|unique:coupons,name',
         ]);
         $requestData = $request->all();

         \DB::transaction(function () use ($request, $requestData){
             if ($request->hasFile('image')) {
                 $requestData['image'] = Coupon::uploadAndResize($request->file('image'));
             }


             Coupon::create($requestData);
         });
 
         toastr()->success(__('coupons.created_success'));
 
         return redirect('admin/coupons');
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         $coupons = Coupon::findOrFail($id);
         return view('admin.coupons.show', compact('coupons'));
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         //Lấy list áp dụng KM
         $apply_target = Coupon::getListApplyTarget();
         //Lấy list loại KM
         $type = Coupon::getListType();

//         //Lấy list user_id
         $user = User::with('profile')->pluck('name','id');
//         $userId = json_decode(Coupon::where('id', $id)->pluck('user_id')->first());
//         $user_id = User::whereIn('id', $userId??[])->pluck('id', 'name');

         $coupons = Coupon::findOrFail($id);
         return view('admin.coupons.edit', compact('coupons', 'apply_target', 'type','user'));
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         $coupons = Coupon::findOrFail($id);
 
         $requestData = $request->all();
 
         if (empty($requestData['active'])){
             $requestData['active'] = config('settings.inactive');
         }

         \DB::transaction(function () use ($request, $requestData, $coupons){
             if($request->hasFile('image')) {
                 \File::delete($coupons->image);
                 $requestData['image'] = Coupon::uploadAndResize($request->file('image'));
             }
             $coupons->update($requestData);
         });
 
         toastr()->success(__('coupons.updated_success'));
         return redirect('admin/coupons');
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         $coupon = Coupon::findOrFail($id);
         if (!empty($coupon->image)){
             \File::delete($coupon->image);
         }
         Coupon::destroy($id);
 
         toastr()->success(__('coupons.deleted_success'));
 
         return redirect('admin/coupons');
     }
}

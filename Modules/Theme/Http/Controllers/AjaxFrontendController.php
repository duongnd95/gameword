<?php

namespace Modules\Theme\Http\Controllers;

use App\Rules\Recaptcha;
use App\Setting;
use Illuminate\Http\Request;
use App\Events\MailContactEvent;
use Illuminate\Routing\Controller;
use App\Contact;
use App\CategoryProduct;
use App\Product;
use App\Review;
use App\Newsletter;

class AjaxFrontendController extends Controller
{
    public function postNewsletter(Request $request){
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email'
            ],
            [
                'email.required' => 'Email không được để trống !',
                'email.unique' => 'Email đã được đăng ký !',
                'email.email' => 'Email không hợp lệ !'
            ]
        );
        if ($validator->passes()){
            $newsletter = new Newsletter();
            $newsletter->email = $request->email;
            $newsletter->save();
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors'=>$validator->errors()->all()]);
    }
    public function postContact(Request $request){
        $validator = \Validator::make($request->all(), [
            'fullname' => 'required',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'required|numeric|min:10',
            'message' => 'required',
            'g-recaptcha-response' => ['required', new Recaptcha()]
        ],
            [
                'email.email' => 'Email không đúng định dạng',
                'email.unique' => 'Email đã được đăng ký',
                'phone.numeric' => 'Số điện thoại phải là số',
                'phone.min' => 'Số điện thoại tối thiểu 10 kí tự',
                'g-recaptcha-response.required' => 'Captcha không được để trống'
            ]);
        if ($validator->passes()){
            $contact = new Contact();
            $contact->fullname = $request->fullname;
            $contact->email = $request->email;
            $contact->address = !empty($request->address) ? $request->address : '';
            $contact->phone = !empty($request->phone) ? $request->phone : '';
            $contact->message = $request->message;
            $contact->save();
            //Send mail
            event(new MailContactEvent($contact));
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors'=>$validator->errors()->all()]);
    }
    //Review
    public function review(Request $request){
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'review' => 'required',
        ],[
            'name.required' => 'Vui lòng nhập họ tên!',
            'review.required' => 'Vui lòng nhập đánh giá!'
        ]);
        if ($validator->passes()){
            Review::create($request->all());
            return response()->json([
                'success' => 'ok'
            ]);
        }
        return response()->json(['errors'=>$validator->errors()]);
    }
    //Pagination Reviews
    public function ajaxPagination(Request $request)
    {
        $listReviews = Review::with('product')->where(['product_id' => $request->id, 'active' => config('settings.active')])->paginate(5);
        $settings = Setting::allConfigsKeyValue();
        return view('theme::front-end.products.reviewajax',compact('listReviews', 'settings'));
    }
    //Filter Product
    public function filterProduct(Request $request){
        $settings = Setting::allConfigsKeyValue();
        $menuProductCategories = CategoryProduct::all();
        $products = new Product();
        $products = $products->with('category');
        $idCategory = $request->get('category_id');
        if (!empty($idCategory)){
            $products = $products->where('category_id',$idCategory);
        }
        $order = $request->get('order');
        switch ($order) {
            case 'price':
                $products = $products->orderBy('price', 'ASC');
                break;
            case 'price-desc':
                $products = $products->orderBy('price', 'DESC');
                break;
            default:
                $products = $products->orderBy('updated_at','DESC');
        }
        $products = $products->paginate(config('settings.paginate.page12'));
        return view('theme::front-end.products.filterajax', compact('products', 'settings', 'menuProductCategories'));
    }

}

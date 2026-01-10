<?php

namespace Modules\Theme\Http\Controllers;

use App\AboutUs;
use App\CompanySetting;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Category;
use Modules\Booking\Entities\Customer;
use Modules\Booking\Entities\Booking;
use App\News;
use App\Page;
use Modules\Product\Entities\CategoryProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Review;
use Modules\Promotion\Entities\Promotion;
use App\SysMenu;
use Modules\Theme\Entities\DefineKeyword;
use Modules\Theme\Entities\Partner;
use Modules\Theme\Entities\Slider;
use Illuminate\Support\Facades\Auth;
use Session;


class FrontendController extends Controller
{
    public function __construct()
    {
        $mainMenus = Category::with('parent')->orderBy('created_at')->get();
        $bottomMenus = $this->menu(2);
        $settings = Setting::allConfigsKeyValue();
        $sliders = Slider::where('active', config('settings.active'))->orderBy('arrange', 'ASC')->take(5)->get();
        $news_viewer = News::with('category')->orderByDesc('view')->take(5)->get();
        $pages = Page::all();


        \View::share([
            'mainMenus' => $mainMenus,
            'bottomMenus' => $bottomMenus,
            'settings' => $settings,
            'keywords' => $this->getKeyword(),
            'news_viewer' => $news_viewer,
            'sliders' => $sliders,
            'pages' => $pages
        ]);
    }

    public function getKeyword()
    {
        $keywords = DefineKeyword::defineKeywordKeyValue();
        return $keywords;
    }
    public function menu($position = 1)
    {
        $menus = SysMenu::with('parent')->where('position', 'LIKE', "%$position%")->orderBy('arrange', 'ASC')->get();
        return $menus;
    }

    public function getNewsFocus($perPage)
    {
        $newsLast = News::with('category')->where('image', '<>', '')->latest()->first();
        return News::with('category')->where(['active' => config('settings.active')])->orderBy('updated_at', 'DESC')->take($perPage)->get();
    }


    public function index()
    {   
//        $news = $this->getNewsFocus(config('settings.paginate.page10'));
        $newsHot = News::with('category')->latest()->take(6)->get();
        $categories = Category::with('parent', 'gallery')->whereNull('parent_id')->get();
        return view('theme::front-end.layouts.home', compact('newsHot', 'categories'));
    }

    public function getListParents($slugParent)
    {
         if(is_numeric($slugParent)){
            $news = News::with('category')->where(['active' => config('settings.active'),['id', $slugParent]])->first();
            if(!$news) return view("theme::front-end.404");
            $category = Category::where('id', $news->category_id)->first();
            //Increment view
            News::find($news->id)->increment('view');
            $otherNews = News::with('category')->where([['category_id', '=', $category->id], ['active', '=', config('settings.active')], ['id', '<>', $news->id]])->orderBy('updated_at', 'DESC')->take(1)->get();
            return view("theme::front-end.news.detail", compact('news', 'category', 'otherNews'));
        }
        $category = Category::where('slug', $slugParent)->first();
        $slugCategoryParent = !empty($category) ? $category->slug : '';
        switch ($slugParent) {
            case $slugCategoryParent:
//                $categoryIds = Category::where('parent_id', $category->id)->pluck('id')->toArray();
                $categories = Category::with('parent')->where('parent_id', $category->id)->where('active', config('settings.active'))->get();
//                $news = News::with('category')->whereIn('category_id', $categoryIds)->where('active', config('settings.active'))->orderBy('updated_at', 'DESC')->paginate(config('settings.paginate.page12'));
                return view('theme::front-end.news.list-parent', compact('categories','category'));
            default:
                return view("theme::front-end.404", compact('slugParent'));
        }
    }

    public function getListChild($slugParent, $slugChild)
    {
        $category = Category::where('slug', $slugParent)->first();
        $slugCategory = [];
        if ($category) {
            $slugCategory = Category::where('parent_id', $category->id)->pluck('slug')->toArray();
        }

        if (!empty($categoryProduct)) {
            $slugCategoryProduct = CategoryProduct::where('parent_id', $categoryProduct->id)->pluck('slug')->toArray();
        } else {
            $slugCategoryProduct = CategoryProduct::where('slug', $slugChild)->pluck('slug')->toArray();
        }
        switch ($slugChild) {
            case in_array($slugChild, $slugCategory):
                $category = Category::where('slug', $slugChild)->first();
                $news = News::with('category')->where(['active' => config('settings.active'), 'category_id' => $category->id])->orderBy('updated_at', 'DESC')->paginate(config('settings.paginate.page8'));
                return view('theme::front-end.news.list-child', compact('category', 'news'));
            case in_array($slugChild, $slugCategoryProduct):
                $category = CategoryProduct::where('slug', $slugChild)->first();
                $products = Product::with('category')->where(['category_id' => $category->id, 'active' => config('settings.active')])->orderBy('updated_at', 'DESC')->paginate(config('settings.paginate.page12'));
                return view('theme::front-end.products.list-child', compact('category', 'products'));
            default:
                return view("theme::front-end.404", compact('slugChild'));
        }
    }

    public function getDetail($slugParent, $slugDetail)
    {
        $slugMenu = SysMenu::where('slug', $slugParent)->pluck('slug')->toArray();
        $slugCategory = Category::where('slug', $slugParent)->pluck('slug')->toArray();
        switch ($slugParent) {
            case in_array($slugParent, $slugCategory):
                $category = Category::where(['slug' => $slugParent, 'active' => config('settings.active')])->first();
                $news = News::with('category')->where([['category_id', $category->id], 'active' => config('settings.active'),['slug', $slugDetail]])->first();
                if(!$news) return view("theme::front-end.404", compact('slugParent', 'slugDetail'));
                //Increment view
                News::find($news->id)->increment('view');
                $otherNews = News::with('category')->where([['category_id', '=', $category->id], ['active', '=', config('settings.active')], ['id', '<>', $news->id]])->orderBy('updated_at', 'DESC')->take(1)->get();
                return view("theme::front-end.news.detail", compact('news', 'category', 'otherNews'));
            case in_array($slugParent, $slugMenu):
                $menu = SysMenu::where(['slug' => $slugParent])->first();
                $page = Page::where([['slug', '=', $slugDetail], ['active', '=', config('settings.active')]])->first();
                return view('theme::front-end.pages.page', compact('page','menu'));
            default:
                return view("theme::front-end.404", compact('slugParent', 'slugDetail'));
        }
    }

    public function getPage($slug)
    {
        $page = Page::where([['slug', '=', $slug], ['active', '=', config('settings.active')]])->first();
        $menu = SysMenu::where([['slug', '=', $slug]])->first();
        if($menu != null){
            switch ($slug){
                case "lien-he":
                    return view('theme::front-end.pages.contact', compact('page','menu'));
                default:
                    return view('theme::front-end.pages.page', compact('page','menu'));
            }
        }
        else
            return view("theme::front-end.404");
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $news = News::with('category');
        if (!empty($query)) {
            $news = $news->where('title', 'LIKE', "%$query%")
                ->orWhere('description', 'LIKE', "%$query%")
                ->orWhere('content', 'LIKE', "%$query%");
        }
        $news = $news->paginate(config('settings.paginate.page12'));

        return view('theme::front-end.products.search', compact('news', 'query'));
    }


}
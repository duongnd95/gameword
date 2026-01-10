<?php

namespace Modules\Theme\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\News;
use App\Category;
use App\Page;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\CategoryProduct;
use Illuminate\Routing\Controller;

class SiteMapController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
      $news = News::all();
      $category = Category::all();
      $products = Product::all();
      $categoryProducts = CategoryProduct::all();
      $pages = Page::all();
      
      return response()->view('theme::front-end.sitemap.index', [
          'news' => $news,
          'category' => $category,
          'products' => $products,
          'categoryProducts' => $categoryProducts,
          'pages' => $pages,
      ])->header('Content-Type', 'text/xml');
    }
   
}

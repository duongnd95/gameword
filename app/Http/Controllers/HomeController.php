<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use App\News;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::count();
        $news = News::count();
        $users = User::count();
        return view('adminlte::home', compact('news', 'categories', 'users'));
    }
}

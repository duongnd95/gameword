<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\Authorizable;
use App\Http\Controllers\Controller;
use Modules\Product\Entities\Review;
use Modules\Product\Entities\Product;


class ReviewController extends Controller
{

    use Authorizable;
    /**
     * Display a listing of the resource.
     * @return Response
     */
     public function index(Request $request)
     {

         $keyword = $request->get('search');
         $reviews = Review::with('product');
         if (!empty($keyword)){
            $reviews = Review::where('name','LIKE',"%$keyword%");
        }
         $reviews = $reviews->paginate(20);

         return view('product::reviews.index', compact('reviews'));
     }

    

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $review = Review::findOrFail($id);
        return view('product::reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('product::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    
}

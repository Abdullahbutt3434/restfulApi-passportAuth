<?php

namespace App\Http\Controllers;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends ApiController
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['index','show']);
    }


    public function index(Request $request){
        $sellers = Seller::has('products')->get();
        return response($sellers);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {

        return $this->showOne($seller,202);
    }
}

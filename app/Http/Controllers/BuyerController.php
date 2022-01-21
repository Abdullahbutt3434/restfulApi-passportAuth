<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BuyerController extends ApiController
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['index','show']);
    }

    public function index(){
        $buyers = Buyer::has('transactions')->get();
//        return response()->json(['data' => $buyers, 'code' => 202]);

        return $this->showAll($buyers,200);


    }


    public function show(Buyer $buyer){
//        try {
//            $buyers = Buyer::has('transactions')->findOrFail($id);
//        }catch (\Exception $exception){
//            return $this->errorResponse($exception->getMessage(),404);
//        }
        return $this->showOne($buyer);


    }
}

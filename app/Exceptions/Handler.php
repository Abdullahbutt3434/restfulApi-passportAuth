<?php

namespace App\Exceptions;

use App\Trait\ApiResponser;
use http\Env\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Mockery\Exception\InvalidOrderException;
use Nette\Schema\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {/*
        $this->reportable(function (Throwable $e) {
            //
        });*/

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            Log::channel('MethodNotAllowedHttpException')->info($e);
            return response()->json('this is MethodNotAllowedException',$e->getStatusCode());
        });

        $this->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            return response()->json($e->errors().'this is ValidationException ',422);
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json($e->errors().'this is ModelNotFoundException ',404);
        });

        $this->renderable(function (UnauthorizedException $e, $request) {
//            Log::channel('MethodNotAllowedHttpException')->info($e);
            return response()->json('this is UnauthorizedException',$e->getStatusCode());
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            return response()->json($e->errors().'this is AuthorizationException ',401 );
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            Log::channel('Authentication')->info($e);
            return response()->json(" authentication Issue",401 );
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {

            Log::channel('HttpNotFound')->info($e);
            return response()->json('Not exist this is NotFoundHttpException ',$e->getStatusCode() );
        });





        $this->renderable(function (\HttpException $e, $request) {
            return response()->json($e->errors().'this is HttpException ',405  );
        });


        $this->renderable(function (QueryException $e, $request) {
            if($e->errorInfo['1'] == 1451){
                return response()->json("Cannot Remove this resource permanently. it is related with any other resource",403  );
            }
            return response()->json($e->errors().'this is QueryException ',405  );
        });

//        $this->renderable(function (\Exception $e, $request){
//
////            return response()->json("Unkown exception.. Try later",500  );
//            return response()->json($e->getMessage().'this is General Exception ',500  );
//        });
    }
}

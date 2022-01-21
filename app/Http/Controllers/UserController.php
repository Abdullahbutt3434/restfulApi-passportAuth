<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Transformers\UserTransformer;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;
use function GuzzleHttp\Promise\all;

class UserController extends ApiController
{


    public function __construct()
    {
        $this->middleware('auth:api')->only(['index','show','store','destory']);
    }


    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::all();
        return $this->showAll($users,200);
    }


    public function show(User $user): \Illuminate\Http\JsonResponse
    {
        return $this->showOne($user , 200);
    }



    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ];

        try {
            $this->validate($request, $rules);
        }catch (\Exception $exception){
             return $this->errorResponse($exception->errors(),406);
        }



        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);
        return $this->showOne($user,202);
    }



    public function update(Request $request,User $user): \Illuminate\Http\JsonResponse
    {

        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        try {
            $this->validate($request, $rules);
        }catch (\Illuminate\Validation\ValidationException $exception){
            return $this->errorResponse($exception->errors(),406);
        }




        if ($request->has('name')) {
            $user->name = $request->name;
        }



        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }



        if ($request->has('admin')) {

            if (!$user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field',409);
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) {
            $this->errorResponse('You need to specify a different value to update', 422);
        }

        $user->save();


        return $this->showOne($user,200);
    }

    public function destroy(User $user){
        $user->delete();
        return $this->successResponse('user Deleted Successfully',202);

    }


    public function login(Request $request){

        $login_credentials=[
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(auth('web')->attempt($login_credentials)){
            //generate the token for the user
            $user_login_token= auth('web')->user()->createToken('PassportExample@Section.io')->accessToken;
            //now return this token on success login attempt

            return response()->json(['token' => $user_login_token,
                                    'user'=>\auth()->user()], 200);
        }
        else{
            //wrong login credentials, return, user not authorised to our system, return error code 401
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }



        return response()->json([
            'data'=>Auth::user(),
            'access_token'=>$tokenResult
        ]);

    }


    public function register(Request $request){
        $rules = [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        try {
            $this->validate($request, $rules);
        }catch (\Exception $exception){

            return $this->errorResponse($exception->errors(),406);
        }

        try {
            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ]);
        }catch(\Exception $e){
            return $this->errorResponse($e->errors(),406);
        }

        // Creating a token without scopes...
        $token = $user->createToken('Token Name')->accessToken->token;


        return response()->json([
            'data'=>$request->all() ,
            'Token' => $token
        ]);

    }
}

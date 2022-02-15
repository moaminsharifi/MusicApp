<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\CustomResponse;
use Hash;
class AuthController extends Controller
{
    /**
     * Create user
     *
     * @return string message
     */
    public function signup()
    {

        $attributes = request()->validate([
            'name' => 'required|string|max:180',
            'email' => 'required|string|email|unique:users|max:180',
            'password' => 'required|string|max:180',
            'password_confirm'=> 'required|same:password|max:180'
        ]);
        $attributes['password'] =  Hash::make($attributes['password']);
        $user = User::create(
            $attributes
        );



        $success = $user->getUserData();
        $success['token'] =  $user->createToken('authToken')->plainTextToken;
        $success['token_type'] = 'Bearer';
        return CustomResponse::createSuccess($success);

    }

    /**
     * Login user and create token
     *
     *
     * @return CustomResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @return CustomResponse with Error
     */
    public function login()
    {
        $attributes = request()->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);


        $user = User::where('email', $attributes['email'])->first();
        abort_unless($user, CustomResponse::createError('10001') );
        abort_unless(Hash::check($attributes['password'], $user->password), CustomResponse::createError('10002') );


        $success = $user->getUserData();
        $success['token'] =  $user->createToken('authToken')->plainTextToken;
        $success['token_type'] = 'Bearer';

        return CustomResponse::createSuccess($success);





    }

    /**
     * Logout user (Revoke the token)
     *
     * @return string message
     */
    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        return CustomResponse::createSuccess('Successfully logged out');


    }

    /**
     * Get the authenticated User
     *
     * @return json user object
     */
    public function user()
    {
        return CustomResponse::createSuccess(request()->user()->getUserData());
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\CustomResponse;
use Hash;
use App\Http\Requests\API\AuthController\SingUpRequest;
use App\Http\Requests\API\AuthController\LoginRequest;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @return CustomResponse|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @return CustomResponse with Error
     */
    public function signup(SingUpRequest $request)
    {

        $attributes = $request->validated();
        $attributes['password'] =  Hash::make($attributes['password']);
        $user = User::create(
            $attributes
        );

        $success = $user->toArray();
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
    public function login(LoginRequest $request)
    {
        $attributes = $request->validated();


        $user = User::where('email', $attributes['email'])->first();
        abort_unless($user, CustomResponse::createError('10001') );
        abort_unless(Hash::check($attributes['password'], $user->password), CustomResponse::createError('10002') );


        $success = $user->toArray();
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
        return CustomResponse::createSuccess(request()->user()->toArray());
    }
}

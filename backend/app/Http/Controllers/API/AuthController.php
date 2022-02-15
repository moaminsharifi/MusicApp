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
     * Register
     *
     * This endpoint lets to register and get your token.
     *
     * @bodyParam name string required  user name.
     * @bodyParam email string required  user email.
     * @bodyParam password string required user password.
     * @bodyParam password_confirm string required user password confirm.
     * @responseFile status=200 docs/responses/auth/user.singup.json
     * @responseFile status=400 scenario="Password not match - 00004"  docs/responses/errors/00004.json
     * @responseFile status=400 scenario="Email exist in database - 00004"  docs/responses/errors/00004.json
     *
     * @return CustomResponse
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
     * Login
     *
     *
     * This endpoint lets to Login and get your token.
     *
     *
     * @bodyParam email string required  user email.
     * @bodyParam password string required user password.

     * @responseFile status=200 docs/responses/auth/user.login.json
     * @responseFile status=404 scenario="User Not found - 10001"  docs/responses/errors/10001.json
     * @responseFile status=400 scenario="Form validation - 00004"  docs/responses/errors/00004.json
     *
     * @return CustomResponse
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
     * Logout
     *
     * This endpoint get token and make it invalid.
     *
     *
     * @authenticated
     * @responseFile status=200 docs/responses/auth/user.logout.json
     * @responseFile status=403 scenario="Token Invalid - 10004"  docs/responses/errors/10004.json
     *
     * @return CustomResponse
     */
    public function logout()
    {
        request()->user()->currentAccessToken()->delete();
        return CustomResponse::createSuccess('Successfully logged out');


    }

    /**
     * User Data
     *
     * This endpoint get token and return user data.
     *
     *
     * @authenticated
     * @responseFile status=200 docs/responses/auth/user.userdata.json
     * @responseFile status=403 scenario="Token Invalid - 10004"  docs/responses/errors/10004.json
     *
     * @return CustomResponse
     */
    public function user()
    {
        return CustomResponse::createSuccess(request()->user()->toArray());
    }
}

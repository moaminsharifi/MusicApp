<?php

namespace Tests\Feature;

use App\Helpers\CustomResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Str;
use Tests\TestCase;
use Database\Factories\UserAPIFactory;
class UserApiTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     * @group user
     * @return void
     */
    public function registerLoginLogoutChecker()
    {
        $user = UserAPIFactory::make();
        $jsonStructForLogin = [
            'ok',
            'data'=>[
                'token',
                'token_type',
                'is_admin',
                'name',
                'email'
            ]
        ];
        $response = $this->json('POST' , 'api/auth/signup', $user);

        $response->assertStatus(200)
            ->assertJsonStructure(
                $jsonStructForLogin
        );
        $this->assertDatabaseHas('users', ['email'=>$user['email']]);


        /**
         * Check Login
         */
        $response = $this->json('POST','/api/auth/login', $user)
            ->assertStatus(200)
            ->assertJsonStructure($jsonStructForLogin);



        /**
         * Check Logout
         */
        $responseAsObject = json_decode($response->getContent());
        $mainUserHeader = [
            'HTTP_Authorization' => 'Bearer ' . $responseAsObject->data->token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];



        $response = $this->json('GET','/api/auth/logout', $user , $mainUserHeader);

        $response->assertStatus(200)
        ->assertJsonStructure(
            [
                'ok',
                'data'
            ]
        );


        /**
         * Check Invalid Password
         */
        $userWithInValidPassword = $user;
        $userWithInValidPassword['password'] = Str::random(20);
        $response = $this->json('POST','/api/auth/login', $userWithInValidPassword);

        $response->assertStatus(400)
                 ->assertJson(
                    CustomResponse::createErrorString('10002')
                    );
        /**
         * Check user Data with token
         */
        $response = $this->json('POST','/api/auth/login', $user);
        $responseAsObject = json_decode($response->getContent());
        $mainUserHeader = [
             'HTTP_Authorization' => 'Bearer ' . $responseAsObject->data->token,
             'Accept' => 'application/json',
             'Content-Type' => 'application/json',
         ];
        $response = $this->json('GET','/api/auth/user', $mainUserHeader);
        $jsonStructForGetUserData = [
            'ok',
            'data'=>[
                'is_admin',
                'name',
                'email'
            ]
        ];

        $response->assertStatus(200)
            ->assertJsonStructure(
                $jsonStructForGetUserData
            );



    }
}

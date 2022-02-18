<?php

namespace Tests\Feature\User\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Factories\UserAPIFactory;
use Illuminate\Http\Response;
use App\Models\User;
class Logout extends TestCase
{
    use RefreshDatabase;
    private $jsonStructUserData =  [
            'ok',
            'data'=>[
                'token',
                'token_type',
                'is_admin',
                'name',
                'email'
            ]
    ];

     /**
     * A test for successfully logout
     * @test
     * @group user
     * @group user.logout
     * @return void
     */
    public function success(){
        // prepare data
         $user = User::factory()->create();
         $data = [
            'email' =>$user->email,
            'password'=>'password'];
        // before send assertion

        // send request
        $response = $this->json('POST' , route('api.login'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                $this->jsonStructUserData
        );

        // prepare data
        $token = json_decode($response->getContent())->data->token;
        $header = [
            'HTTP_Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // before send assertion

        // send request
        $response = $this->json('GET',route('api.logout'), [], $header);
        // after send request assertion
        $response->assertStatus(200)
        ->assertJsonStructure(
            [
                'ok',
                'data'
            ]
        );

    }
    /**
     * A test for invalid_token
     * @test
     * @group user
     * @group user.logout
     * @return void
     */
    public function invalid_token(){
        // prepare data
         $user = User::factory()->create();
         $data = [
            'email' =>$user->email,
            'password'=>'password'];
        // before send assertion

        // send request
        $response = $this->json('POST' , route('api.login'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                $this->jsonStructUserData
        );

        // prepare data
        $token = 'RANDOM_TOKEN';
        $header = [
            'HTTP_Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        // before send assertion

        // send request
        $response = $this->json('GET',route('api.logout'), [], $header);
        // after send request assertion
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00005",
                'message'=>"Unauthenticated.",
            ],
            'data'=>[

            ]
            ]);



    }




}

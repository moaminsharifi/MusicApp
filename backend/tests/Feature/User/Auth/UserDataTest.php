<?php

namespace Tests\Feature\User\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Factories\UserAPIFactory;
use Illuminate\Http\Response;
use App\Models\User;

class UserData extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
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
     * A test for successfully getUserData
     * @test
     * @group user
     * @group user.data
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
        $response = $this->json('GET',route('api.userData'), [], $header);
        print_r($response->getContent());
        // after send request assertion
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                $this->jsonStructUserData
        );

    }
}

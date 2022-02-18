<?php

namespace Tests\Feature\User\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Factories\UserAPIFactory;
use Illuminate\Http\Response;
use App\Models\User;

class Login extends TestCase
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
     * A test for successfully login
     * @test
     * @group user
     * @group user.login
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
        // Hash::shouldReceive('make')->once();
        $this->assertDatabaseCount('users',1);
        $this->assertDatabaseHas('users',[
            'email'=>$user['email']
        ]);
    }
    /**
     * A test for checking email validation
     * @test
     * @group user
     * @group user.login
     * @return void
     */
    public function email_validation(){
        // prepare data

        $data = ['email'=>''];

        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.login'), $data);
        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password','message'=>'The password field is required.'],
            ]
            ]);

         // prepare data (invalid email)
         $data = ['email'=>'amin.com'];
         // before send assertion

        // send request
        $response = $this->json('POST' , route('api.login'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'email','message'=>'The email must be a valid email address.'],
                ['field'=>'password','message'=>'The password field is required.'],
            ]
            ]);

        // prepare data (email which not found in database)
         $data = ['email'=>$this->faker->unique()->safeEmail(), 'password'=>'password'];
         // before send assertion

        // send request
        $response = $this->json('POST' , route('api.login'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"10001",
                'message'=>"User Not Found",
            ],
            'data'=>[
            ]
            ]);


    }
    /**
     * A test for checking password validation
     * @test
     * @group user
     * @group user.login
     * @return void
     */
    public function password_validation(){
        // prepare data
        $user = User::factory()->create();
        $data = ['password'=>''];

        // before send assertion
        $this->assertDatabaseCount('users',1);
        // send request
        $response = $this->json('POST' , route('api.login'), $data);
        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password','message'=>'The password field is required.'],
            ]
        ]);
        // prepare data (password not match)
        $user = User::factory()->create();
        $data = ['password'=>'password_NOT_MATCH','email'=>$user->email];

        // before send assertion
        $this->assertDatabaseCount('users',2);
        // send request
        $response = $this->json('POST' , route('api.login'), $data);
        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"10002",
                'message'=>"Password Not Correct",
            ],
            'data'=>[
            ]
            ]);


    }

}

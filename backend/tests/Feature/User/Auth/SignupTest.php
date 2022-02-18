<?php

namespace Tests\Feature\User\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Factories\UserAPIFactory;
use Illuminate\Http\Response;
use App\Models\User;
class SignupTest extends TestCase
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
     * A test for successfully signup
     * @test
     * @group user
     * @group user.signup
     * @return void
     */
    public function success(){
        // prepare data
         $user = UserAPIFactory::make();
        // before send assertion
        $this->assertDatabaseCount('users',0);

        // send request
        $response = $this->json('POST' , route('api.signup'), $user);
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
     * A test for checking validation
     * @test
     * @group user
     * @group user.signup
     * @return void
     */
    public function name_validation(){
        // prepare data (empty name)
        $data = ['name'=>''];
        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name field is required.'],
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password','message'=>'The password field is required.'],
                ['field'=>'password_confirm','message'=>'The password confirm field is required.'],
            ]
            ]);
        // prepare data (less than 3 char)
        $data = ['name'=>'na'];
        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);
        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name must be at least 3 characters.'],
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password','message'=>'The password field is required.'],
                ['field'=>'password_confirm','message'=>'The password confirm field is required.'],
            ]
            ]);
            $this->assertDatabaseCount('users',0);
    }
    /**
     * A test for checking email validation
     * @test
     * @group user
     * @group user.signup
     * @return void
     */
    public function email_validation(){
        // prepare data (empty email)
        $data = ['email'=>''];
        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name field is required.'],
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password','message'=>'The password field is required.'],
                ['field'=>'password_confirm','message'=>'The password confirm field is required.'],
            ]
            ]);
        // prepare data (invalid email)

        $data = ['email'=>'amin.com'];
        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name field is required.'],
                ['field'=>'email','message'=>'The email must be a valid email address.'],
                ['field'=>'password','message'=>'The password field is required.'],
                ['field'=>'password_confirm','message'=>'The password confirm field is required.'],
            ]
            ]);



        // prepare data (email exist in database)
        $user = User::factory()->create();
        $data = ['email'=>$user->email];
        // before send assertion
        $this->assertDatabaseCount('users',1);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);
        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name field is required.'],
                ['field'=>'email','message'=>'The email has already been taken.'],
                ['field'=>'password','message'=>'The password field is required.'],
                ['field'=>'password_confirm','message'=>'The password confirm field is required.'],
            ]
            ]);
        $this->assertDatabaseCount('users',1);
    }
    /**
     * A test for checking password validation
     * @test
     * @group user
     * @group user.signup
     * @return void
     */
    public function password_validation(){
        // prepare data (empty password)
        $data = ['password'=>''];
        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);

        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name field is required.'],
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password','message'=>'The password field is required.'],
                ['field'=>'password_confirm','message'=>'The password confirm field is required.'],
            ]
            ]);
        // prepare data (less than 6 char)
        $data = ['password'=>'na123'];
        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);
        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name field is required.'],
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password','message'=>'The password must be at least 6 characters.'],
                ['field'=>'password_confirm','message'=>'The password confirm field is required.'],
            ]
            ]);
            $this->assertDatabaseCount('users',0);

        // prepare data (password and confirm password not match)
        $data = ['password'=>'gWHVWkH5tvyvxkNV', 'password_confirm'=>'gWHVWkH5tvyvxkNV_NOT_MATCH'];
        // before send assertion
        $this->assertDatabaseCount('users',0);
        // send request
        $response = $this->json('POST' , route('api.signup'), $data);
        // after send request assertion
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            'ok'=>0,
            'error'=>[
                'code'=>"00004",
                'message'=>"Invalid Input For Submit",
            ],
            'data'=>[
                ['field'=>'name','message'=>'The name field is required.'],
                ['field'=>'email','message'=>'The email field is required.'],
                ['field'=>'password_confirm','message'=>'The password confirm and password must match.'],
            ]
            ]);
            $this->assertDatabaseCount('users',0);
    }




}

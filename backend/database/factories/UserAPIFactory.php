<?php
namespace Database\Factories;

use App\Models\Model;
use Str;
class UserAPIFactory
{



    /**
     * Make New model for api requests
     *
     * @return array
     */
    public static function make()
    {
        $faker = \Faker\Factory::create();
        $password = Str::random(32);
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'password' => $password,
            'password_confirm'=> $password,
        ];
    }
}

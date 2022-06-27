<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserAuthTest extends TestCase
{
    public function test_required_fields_for_registration()
    {
        $this->json('POST', 'api/v1/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
              "errors" => [
                    "name" => ["The name field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    public function test_successful_registration()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345",

        ];

        $this->json('POST', 'api/v1/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],

                "message"
            ]);
    }

    public function test_must_enter_email_and_password()
    {
        $this->json('POST', 'api/v1/login')
            ->assertStatus(422)
            ->assertJson([
                   "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
            ]);
    }

    public function test_successful_login()
    {
        User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
         ]);
        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $this->json('POST', 'api/v1/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                 "access_token",

            ]);

        $this->assertAuthenticated();
    }
}

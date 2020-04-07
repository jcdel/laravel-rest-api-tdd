<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function testRegister()
    {
        // Create test user data
        $data = [
            'email' => 'test@gmail.com',
            'name' => 'Test',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ];

        // Send a post request
        $response = $this->postJson(route('api.register'), $data);

        $response
            ->assertStatus(200)
            ->assertJson($response->json());
    }

    public function testLogin()
    {
        // Creating Users
        User::create([
            'name' => 'test',
            'email'=>'test@gmail.com',
            'password' => bcrypt('secret1234')
        ]);
        
        // Simulate login using created user
        $response = $this->postJson(route('api.login'), [
            'email' => 'test@gmail.com',
            'password' => 'secret1234',
        ]);

        //$response->dump();

        $data = [
            'token_type' => 'bearer'
        ];

        $response
            ->assertStatus(200)
            ->assertJson($response->json())
            ->assertJsonFragment($data);
    }
}

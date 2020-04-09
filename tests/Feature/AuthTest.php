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
        $user = factory(User::class)->create();

        $this->postJson(route('api.register'), [
            'name' => $user->name,
            'email' => 'test@gmail.com',
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ])
        ->assertCreated()
        ->assertJsonStructure(['email']);

    }

    public function testLogin()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt($password = 'secret1234')
        ]);

        $this->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => $password,
        ])
        ->assertOk()
        ->assertJsonStructure(['token']);
        $this->assertAuthenticatedAs($user);
    }

    public function testLogout()
    {
        $user = factory(User::class)->create();
        $token = \JWTAuth::fromUser($user);

        $this->postJson(route('api.logout').'?token='.$token)
            ->assertOk()
            ->assertJsonStructure(['message']);
    }

    public function testLogoutUnAuthorizedUser()
    {
        $response = $this->postJson(route('api.logout'))
            ->assertUnauthorized();
            $this->assertEquals('Unauthenticated.', $response['message']);
    }
    
}

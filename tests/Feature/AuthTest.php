<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public function test_register_true()
    {

        $userData = [
            'f_name' => fake()->name(),
            'l_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->postJson('/api/register', $userData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }
    public function test_login_true()
    {
        $user = User::factory()->create();
        $userData = [
            'email' => $user->email,
            'password' => 'password',
        ];
        $response = $this->postJson('/api/login', $userData);
        $response->assertStatus(200);
    }

    public function test_logout_true()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token,])
            ->postJson('/api/logout');
        $response->assertStatus(200);
    }
}

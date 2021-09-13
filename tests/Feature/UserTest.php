<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'johndoe',
            'email' => 'johndoe@gmail.com',
            'password' => 'Abc123456',
            'password_confirmation' => 'Abc123456'
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'success'
            ])
            ->assertHeader('jwt');
    }

    public function test_login()
    {
        User::factory()->create([
            'email' => 'johndoe@gmail.com',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'Abc123456'
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'success'
            ])
            ->assertHeader('jwt');
    }

    public function test_user_not_found()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'abc123456'
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'user not found'
            ])
            ->assertHeaderMissing('jwt');
    }

    public function test_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'johndoe@gmail.com',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'Abc12345'
        ]);

        $response
            ->assertStatus(400)
            ->assertExactJson([
                'message' => 'invalid credentials'
            ])
            ->assertHeaderMissing('jwt');
    }

    public function test_logout()
    {
        User::factory()->create([
            'email' => 'johndoe@gmail.com',
        ]);
        $login = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'Abc123456'
        ]);
        $token = $login->headers->all()['jwt'][0];
        $response = $this->postJson('/api/logout', [], ['jwt' => $token]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Logout successfull'
        ]);
    }

    public function test_change_password()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/changePassword', [
            'old_password' => 'Abc123456', //used in UserFactory
            'password' => 'Abc1234567',
            'password_confirmation' => 'Abc1234567'
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Password changed'
        ]);
    }

    public function test_forgot_password()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/forgotPassword', [
            'email' => $user->email
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'Reset link sent'
        ]);
    }

    public function test_update_account()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->patchJson('/api/editAccount', [
            'email' => 'anotheremail@example.com',
            'name' => 'anothername',
            'password' => 'Abc123456' //used in UserFactory
        ]);

        $response->assertStatus(200)->assertJson([
            'message' => 'User updated successfully'
        ]);
    }

    public function test_delete_account()
    {
        User::factory()->create([
            'email' => 'johndoe@gmail.com',
        ]);
        $login = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'Abc123456'
        ]);
        $token = $login->headers->all()['jwt'][0];
        $response = $this->deleteJson('/api/deleteAccount', [], ['jwt' => $token]);

        $response->assertStatus(200)->assertJson([
            'message' => 'User deleted successfully'
        ]);
    }
}

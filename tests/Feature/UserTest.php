<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'johndoe',
            'email' => 'johndoe@gmail.com',
            'password' => 'abc123456',
            'password_confirmation' => 'abc123456'
        ]);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'success'
            ])
            ->assertHeader('jwt');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends HeartSystemTestCase
{
    public function test_admin_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'admin@sample.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_worker_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'healthwoker@sample.com',
            'password' => 'hw123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->worker);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email'    => 'admin@sample.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_fails_with_unknown_email(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email'    => 'unknown@sample.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $this->actingAs($this->worker);

        $response = $this->get('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}

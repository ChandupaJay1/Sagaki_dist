<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_login_through_api()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Access denied. This app is for representatives only. Admins cannot login here.',
        ]);
    }

    public function test_ref_can_login_through_api()
    {
        $ref = User::create([
            'name' => 'Ref User',
            'email' => 'ref@test.com',
            'password' => Hash::make('password123'),
            'role' => 'ref',
            'is_active' => true,
            'serial_number' => 'SAGAKI-TEST123',
            'serial_expires_at' => now()->addYear(),
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'ref@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Login successful',
        ]);
        $response->assertJsonStructure(['token']);
    }

    public function test_ref_cannot_login_if_not_active()
    {
        $ref = User::create([
            'name' => 'Inactive Ref',
            'email' => 'inactive@test.com',
            'password' => Hash::make('password123'),
            'role' => 'ref',
            'is_active' => false,
            'serial_number' => 'SAGAKI-TEST456',
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'inactive@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_login_through_web()
    {
        $admin = User::create([
            'name' => 'Web Admin',
            'email' => 'webadmin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'webadmin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_ref_cannot_login_through_web()
    {
        $ref = User::create([
            'name' => 'Web Ref',
            'email' => 'webref@test.com',
            'password' => Hash::make('password123'),
            'role' => 'ref',
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'webref@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}

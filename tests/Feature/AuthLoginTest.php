<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;   //migarte db each time
    // use DatabaseTransactions; //test code with new data and rollback all data

    public function test_login_page_loads()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'user'
        ]);

        // or
        // User::create([
        //     'name' => 'Monali',
        //     'email' => 'test@gmail.com',
        //     'password' => Hash::make('123456'),
        //     'role' => 'user'
        // ]);

        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => '123456'
        ]);

        $response->assertRedirect('/myproduct');

        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'user'
        ]);

        $response = $this->post('/login', [
            'email' => 'test@gmail.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_redirects_to_admin_dashboard()
    {
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => '123456'
        ]);

        $response->assertRedirect(route('adminProducts.index'));

        $this->assertAuthenticated();
    }
}
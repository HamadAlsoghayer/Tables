<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_screen()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'employee_number' => $user->employee_number,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post('/api/login', [
            'employee_number' => $user->employee_number,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        $this->withoutExceptionHandling();
        Sanctum::actingAs(User::factory()->create());

        $this->assertAuthenticated();

        $response = $this->get('/api/logout');
        $response->assertSuccessful();

        $this->assertAuthenticated();
    }
    public function test_user_can_retrieve_their_info()
    {

        $this->withoutExceptionHandling();
            $user= Sanctum::actingAs(
                User::factory()->create(),
                ['*']);
    
                $response = $this->get('/api/user');
        
                $this->assertSame($user->name,$response->json('name'));
                $response->assertStatus(200);
    }

    public function test_user_cannot_retrieve_others_info()
    {

        $this->withoutExceptionHandling();
            $user= Sanctum::actingAs(
                User::factory()->create(),
                ['*']);
            $user2 = User::factory()->create();
    
                $response = $this->get('/api/user');
        
                $this->assertNotSame($user2->name,$response->json('name'));
                $response->assertStatus(200);
    }
    
}

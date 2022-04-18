<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TableTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
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

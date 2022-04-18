<?php

namespace Tests\Feature;

use App\Models\Table;
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
    public function test_user_can_retrieve_tables()
    {
        Table::factory(3)->create();

        $this->withoutExceptionHandling();
            $user= Sanctum::actingAs(
                User::factory()->create(),
                ['*']);
    
                $response = $this->get('/api/tables');
        
                $response->assertJsonCount(3,'Tables');
                $response->assertStatus(200);
    }

    public function test_guest_cannot_retrieve_tables_info()
    {
        Table::factory(3)->create();
        $this->assertGuest();

    
                $response = $this->getJson('/api/tables');
                $response->assertUnauthorized();
    }
}

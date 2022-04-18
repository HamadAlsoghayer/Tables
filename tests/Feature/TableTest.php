<?php

namespace Tests\Feature;

use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_user_can_retrieve_tables()
    {
        
        Role::create(['name' => 'admin']);
        Table::factory(3)->create();

        $this->withoutExceptionHandling();
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->getJson('/api/tables');
        
                $response->assertJsonCount(3,'Tables');
                $response->assertStatus(200);
    }
    public function test_non_admin_user_cannot_retrieve_tables()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);

        Table::factory(3)->create();

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('employee'),
                ['*']);
    
                $response = $this->getJson('/api/tables');
                $response->assertForbidden();
    }

    public function test_guest_cannot_retrieve_tables_info()
    {
        Table::factory(3)->create();
        $this->assertGuest();

    
                $response = $this->getJson('/api/tables');
                $response->assertUnauthorized();
    }
}

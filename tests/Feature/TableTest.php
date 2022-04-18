<?php

namespace Tests\Feature;

use App\Models\Reservation;
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

    public function test_admin_user_can_create_tables()
    {
        Role::create(['name' => 'admin']);

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->postJson('/api/tables',['number'=>10,'seats'=>3]);
                $response->assertSuccessful();
    }


    public function test_non_admin_user_cannot_create_tables()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('employee'),
                ['*']);
    
                $response = $this->postJson('/api/tables',['number'=>10,'seats'=>3]);
                $response->assertForbidden();
    }

    public function test_admin_user_cannot_create_tables_with_seats_over_12()
    {
        Role::create(['name' => 'admin']);

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->postJson('/api/tables',['number'=>10,'seats'=>13]);
                $response->assertUnprocessable();
    }

    public function test_admin_user_cannot_create_tables_with_seats_less_than_1()
    {
        Role::create(['name' => 'admin']);

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->postJson('/api/tables',['number'=>10,'seats'=>0]);
                $response->assertUnprocessable();
    }

    public function test_admin_user_cannot_create_tables_with_non_numeric_number()
    {
        Role::create(['name' => 'admin']);

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->postJson('/api/tables',['number'=>'asdf','seats'=>12]);
                $response->assertUnprocessable();
    }
    public function test_admin_user_can_delete_table_using_number()
    {
        Role::create(['name' => 'admin']);
        $table= Table::factory()->create();

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->deleteJson('/api/tables/'.$table->number);
        
                $response->assertStatus(200);
    }

    public function test_non_admin_user_cannot_delete_table_using_number()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);

        $table= Table::factory()->create();

            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('employee'),
                ['*']);
    
                $response = $this->deleteJson('/api/tables/'.$table->number);
        
                $response->assertForbidden();
    }


    public function test_guest_cannot_access_delete_table_endpoint()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);

        $table= Table::factory()->create();

            $this->assertGuest();
    
                $response = $this->deleteJson('/api/tables/'.$table->number);
        
                $response->assertUnauthorized();
    }

    public function test_admin_user_cannot_delete_table_with_reservation()
    {
        
        Role::create(['name' => 'admin']);
        $table= Table::factory()->has(Reservation::factory()->count(1))->create();
        $table->refresh();
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->deleteJson('/api/tables/'.$table->number);
        
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

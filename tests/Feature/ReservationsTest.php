<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReservationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_retrieve_all_reservations()
    {
        
        Role::create(['name' => 'admin']);
        Table::factory()->has(Reservation::factory()->count(3))->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->getJson('/api/reservations');
        
                $response->assertJsonCount(3,'data');
                $response->assertStatus(200);
    }

    public function test_employee_user_cannot_retrieve_all_reservations()
    {

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);
        Table::factory()->has(Reservation::factory()->count(3))->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('employee'),
                ['*']);
    
                $response = $this->getJson('/api/reservations');
        
                $response->assertForbidden();
    }

    public function test_admin_user_can_retrieve_todays_reservations()
    {
        
        Role::create(['name' => 'admin']);
        Table::factory()->has(Reservation::factory(1,['starting_time'=>now(),'ending_time'=>now()->addHour()])->count(3))->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->getJson('/api/reservations/today');
        
                $response->assertJsonCount(3,'data');
                $response->assertStatus(200);
    }

    public function test_employee_user_can_todays_reservations()
    {

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);
        Table::factory()->has(Reservation::factory(1,['starting_time'=>now(),'ending_time'=>now()->addHour()])->count(3))->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('employee'),
                ['*']);
    
                $response = $this->getJson('/api/reservations/today');
        
                $response->assertJsonCount(3,'data');
                $response->assertStatus(200);
    }
}

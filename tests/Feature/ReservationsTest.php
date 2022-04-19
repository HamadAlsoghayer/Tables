<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReservationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_retrieve_all_reservations()
    {
$this->travelTo(today()->setTimeFromTimeString('12:00PM'));

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
        $this->travelTo(today()->setTimeFromTimeString('12:00PM'));

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
             $this->travelTo(today()->setTimeFromTimeString('12:00PM'));
   
        Role::create(['name' => 'admin']);
        Table::factory()->has(Reservation::factory(1,['starting_time'=>Carbon::today()->setTimeFromTimeString('02:00PM'),'ending_time'=>Carbon::today()->setTimeFromTimeString('02:00PM')->addHour()])->count(3))->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->getJson('/api/reservations/today');
        
                $response->assertJsonCount(3,'data');
                $response->assertStatus(200);
    }

    public function test_employee_user_can_todays_reservations()
    {
        $this->travelTo(today()->setTimeFromTimeString('12:00PM'));

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'employee']);
        Table::factory()->has(Reservation::factory(1,['starting_time'=>Carbon::today()->setTimeFromTimeString('02:00PM'),'ending_time'=>Carbon::today()->setTimeFromTimeString('02:00PM')->addHour()])->count(3))->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('employee'),
                ['*']);
    
                $response = $this->getJson('/api/reservations/today');
        
                $response->assertJsonCount(3,'data');
                $response->assertStatus(200);
    }

    public function test_admin_user_can_make_valid_reservations()
    {
        $this->travelTo(today()->setTimeFromTimeString('12:00PM'));
        Role::create(['name' => 'admin']);
        $table = Table::factory()->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->postJson('/api/reservations/',['customer_name'=>'cuz tomer','starting_time'=>Carbon::today()->setTimeFromTimeString('05:00PM')->addMinute(),'ending_time'=>Carbon::today()->setTimeFromTimeString('05:00PM')->addHour(),'table_number'=>$table->number]);
        
                $response->assertStatus(200);
    }

    public function test_admin_user_cannot_make_reservations_starts_in_the_past()
    {
             $this->travelTo(today()->setTimeFromTimeString('12:00PM'));
   
        Role::create(['name' => 'admin']);
        $table = Table::factory()->create();

        
            $user= Sanctum::actingAs(
                User::factory()->create()->assignRole('admin'),
                ['*']);
    
                $response = $this->postJson('/api/reservations/',['customer_name'=>'cuz tomer','starting_time'=>Carbon::now()->addMinutes(-1),'ending_time'=>Carbon::now()->addHour(),'table_number'=>$table->number]);
        
                $response->assertUnprocessable();
    }


    public function test_guest_user_cannot_access_reservations()
    {
             $this->travelTo(today()->setTimeFromTimeString('12:00PM'));
   $this->assertGuest();
                $response = $this->getJson('/api/reservations/today');
        
                $response->assertUnauthorized();
    }

    public function test_guest_user_cannot_access_todays_reservations()
    {
             $this->travelTo(today()->setTimeFromTimeString('12:00PM'));
   $this->assertGuest();
        Table::factory()->has(Reservation::factory(1,['starting_time'=>Carbon::today()->setTimeFromTimeString('02:00PM'),'ending_time'=>Carbon::today()->setTimeFromTimeString('02:00PM')->addHour()])->count(3))->create();

 
    
                $response = $this->getJson('/api/reservations/today');
        
                $response->assertUnauthorized();
    }
}
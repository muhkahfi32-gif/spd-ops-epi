<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Travel;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelTest extends TestCase
{
    use RefreshDatabase;

    public function test_travel_total_amount_multiplies_by_employee_count(): void
    {
        $employee1 = Employee::create([
            'name' => 'Pegawai A',
            'email' => 'pegawai.a@example.com',
            'is_active' => true,
        ]);

        $employee2 = Employee::create([
            'name' => 'Pegawai B',
            'email' => 'pegawai.b@example.com',
            'is_active' => true,
        ]);

        $travel = Travel::create([
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-05',
            'amount' => 500000.00, // Rp 500.000 per orang
            'destination' => 'Jakarta',
            'status' => 'pending',
        ]);

        $travel->employees()->attach([$employee1->id, $employee2->id]);

        $this->assertEquals(2, $travel->employees()->count());
        $this->assertEquals(1000000.00, $travel->total_amount); // 500k * 2 = 1.000.000
    }

    public function test_store_creates_individual_travel_records_per_employee_with_application(): void
    {
        $employee1 = Employee::create([
            'name' => 'Andry',
            'email' => 'andry@example.com',
            'aplikasi' => 'EAM Pembangkit',
            'is_active' => true,
        ]);

        $employee2 = Employee::create([
            'name' => 'Kahfi',
            'email' => 'kahfi@example.com',
            'aplikasi' => 'EAM Pembangkit',
            'is_active' => true,
        ]);

        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->postJson('/travels', [
            'aplikasi' => 'EAM Pembangkit',
            'employee_ids' => [$employee1->id, $employee2->id],
            'start_date' => '2026-08-10',
            'end_date' => '2026-08-12',
            'amount' => 30000,
            'destination' => 'Bandung',
            'status' => 'pending',
        ]);

        $response->assertStatus(200);

        // Expect 2 separate travel records to be created
        $this->assertDatabaseCount('travels', 2);
        
        $travels = Travel::all();
        foreach ($travels as $t) {
            $this->assertEquals('EAM Pembangkit', $t->aplikasi);
            $this->assertEquals(30000, $t->amount);
            $this->assertEquals(1, $t->employees->count());
        }
    }
}

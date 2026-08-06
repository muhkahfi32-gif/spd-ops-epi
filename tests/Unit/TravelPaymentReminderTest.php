<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Travel;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TravelPaymentReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_overdue_travel_scope_selects_unpaid_travels_after_7_days()
    {
        $employee = Employee::create([
            'name' => 'Budi Santoso',
            'nip' => '123456',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // Case 1: Overdue travel (ended 8 days ago, status = pending) -> MUST be selected
        $overdueTravel = Travel::create([
            'destination' => 'Jakarta - Meeting Direksi',
            'start_date' => Carbon::today()->subDays(15),
            'end_date' => Carbon::today()->subDays(8),
            'amount' => 1500000,
            'status' => 'pending',
        ]);
        $overdueTravel->employees()->attach($employee->id);

        // Case 2: Travel ended 3 days ago (not yet H+7) -> MUST NOT be selected
        $recentTravel = Travel::create([
            'destination' => 'Surabaya - Field Visit',
            'start_date' => Carbon::today()->subDays(5),
            'end_date' => Carbon::today()->subDays(3),
            'amount' => 800000,
            'status' => 'pending',
        ]);
        $recentTravel->employees()->attach($employee->id);

        // Case 3: Paid travel ended 10 days ago -> MUST NOT be selected
        $paidTravel = Travel::create([
            'destination' => 'Bandung - Workshop',
            'start_date' => Carbon::today()->subDays(15),
            'end_date' => Carbon::today()->subDays(10),
            'amount' => 2000000,
            'status' => 'paid',
        ]);
        $paidTravel->employees()->attach($employee->id);

        $overdueList = Travel::pendingPaymentOverdue(7)->get();

        $this->assertCount(1, $overdueList);
        $this->assertEquals($overdueTravel->id, $overdueList->first()->id);
    }

    public function test_reminder_command_dry_run_executes_successfully()
    {
        $employee = Employee::create([
            'name' => 'Ahmad Fauzi',
            'nip' => '987654',
            'email' => 'ahmad@example.com',
            'phone' => '089876543210',
            'is_active' => true,
        ]);

        $travel = Travel::create([
            'destination' => 'Semarang - Site Audit',
            'start_date' => Carbon::today()->subDays(10),
            'end_date' => Carbon::today()->subDays(7),
            'amount' => 1200000,
            'status' => 'pending',
        ]);
        $travel->employees()->attach($employee->id);

        $this->artisan('travel:send-reminder --dry-run')
            ->expectsOutputToContain('Menjalankan pengecekan reminder perjalanan dinas belum bayar')
            ->expectsOutputToContain('DRY-RUN')
            ->assertExitCode(0);
    }
}

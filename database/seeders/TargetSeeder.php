<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Target;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $currentYear = date('Y');

        // 1. Buat target untuk setiap employee dengan bulan yang unik
        foreach ($employees as $employee) {
            $targetCount = rand(3, 8); // 3-8 target per employee
            $months = collect(range(1, 12))->shuffle()->take($targetCount);
            
            foreach ($months as $month) {
                Target::factory()
                    ->forEmployee($employee->id)
                    ->forMonth(sprintf('%d-%02d', $currentYear, $month))
                    ->create();
            }
        }
    }
}

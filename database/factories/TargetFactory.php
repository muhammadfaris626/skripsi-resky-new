<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Target>
 */
class TargetFactory extends Factory
{
    protected $model = Target::class;

    public function definition(): array
    {
        // Generate random month within last 2 years
        $startDate = Carbon::now()->subYears(2)->startOfMonth();
        $endDate = Carbon::now()->addMonths(6)->endOfMonth();
        $randomDate = $this->faker->dateTimeBetween($startDate, $endDate);
        $month = Carbon::parse($randomDate)->format('Y-m');

        return [
            // 'employee_id' => Employee::factory(),
            'month' => $month,
            'sale_target' => $this->faker->randomFloat(2, 1000000, 50000000), // 1 juta - 50 juta
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    // State untuk target dengan employee yang sudah ada
    public function forEmployee($employeeId): static
    {
        return $this->state(fn (array $attributes) => [
            'employee_id' => $employeeId,
        ]);
    }

    // State untuk month tertentu
    public function forMonth(string $month): static
    {
        return $this->state(fn (array $attributes) => [
            'month' => $month,
        ]);
    }

    // State untuk tahun tertentu
    public function forYear(int $year): static
    {
        $month = $this->faker->numberBetween(1, 12);
        $monthString = sprintf('%d-%02d', $year, $month);

        return $this->state(fn (array $attributes) => [
            'month' => $monthString,
        ]);
    }

    // State untuk target tahun ini
    public function thisYear(): static
    {
        $currentYear = Carbon::now()->year;
        $month = $this->faker->numberBetween(1, 12);
        $monthString = sprintf('%d-%02d', $currentYear, $month);

        return $this->state(fn (array $attributes) => [
            'month' => $monthString,
        ]);
    }

    // State untuk target month ini
    public function thisMonth(): static
    {
        $monthString = Carbon::now()->format('Y-m');

        return $this->state(fn (array $attributes) => [
            'month' => $monthString,
        ]);
    }

    // State untuk target tinggi (di atas 20 juta)
    public function highTarget(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_target' => $this->faker->randomFloat(2, 20000000, 100000000),
        ]);
    }

    // State untuk target rendah (di bawah 5 juta)
    public function lowTarget(): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_target' => $this->faker->randomFloat(2, 500000, 5000000),
        ]);
    }

    // State untuk target dengan nilai spesifik
    public function withTarget(float $amount): static
    {
        return $this->state(fn (array $attributes) => [
            'sale_target' => $amount,
        ]);
    }

    // State untuk sequence monthan (untuk satu employee)
    public function monthlySequence(int $employeeId, int $year, int $startMonth = 1, int $endMonth = 12): static
    {
        static $currentMonth = null;

        if ($currentMonth === null) {
            $currentMonth = $startMonth;
        }

        $monthString = sprintf('%d-%02d', $year, $currentMonth);
        $currentMonth++;

        if ($currentMonth > $endMonth) {
            $currentMonth = $startMonth;
        }

        return $this->state(fn (array $attributes) => [
            'employee_id' => $employeeId,
            'month' => $monthString,
        ]);
    }
}

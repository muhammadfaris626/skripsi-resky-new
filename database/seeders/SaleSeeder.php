<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\ItemSale;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $products = Product::all();

        if ($employees->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please run EmployeeSeeder and ProductSeeder first');
            return;
        }

        // Create sales for the last 6 months with varying patterns
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();

        // Generate sales for each day
        $invoiceCounter = 1;
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Weekend sales are typically lower
            $isWeekend = $date->isWeekend();
            $minSales = $isWeekend ? 2 : 5;
            $maxSales = $isWeekend ? 8 : 15;

            $dailySalesCount = rand($minSales, $maxSales);

            for ($i = 0; $i < $dailySalesCount; $i++) {
                // Random time during business hours
                $saleTime = $date->copy()->addHours(rand(8, 20))->addMinutes(rand(0, 59));

                $invoiceNumber = 'INV-' . $saleTime->format('Ymd') . '-' . str_pad($invoiceCounter, 4, '0', STR_PAD_LEFT);
                $invoiceCounter++;
                $paymentMethods = ['cash', 'card', 'transfer'];

                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'date' => $saleTime->format('Y-m-d'),
                    'employee_id' => $employees->random()->id,
                    'total_amount' => '0', // Will be calculated after adding items
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'created_at' => $saleTime,
                    'updated_at' => $saleTime,
                ]);

                // Add 1-5 items per sale
                $itemCount = rand(1, 5);
                $totalAmount = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $unitPrice = (float) $product->selling_price;
                    $subtotal = $quantity * $unitPrice;
                    $totalAmount += $subtotal;

                    ItemSale::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $unitPrice,
                        'amount' => $subtotal,
                        'created_at' => $saleTime,
                        'updated_at' => $saleTime,
                    ]);
                }

                // Update sale total
                $sale->update(['total_amount' => (string) $totalAmount]);
            }
        }

        $this->command->info('Sales seeded successfully with realistic data patterns');
    }
}

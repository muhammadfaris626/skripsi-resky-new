<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\ItemPurchase;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
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

        $suppliers = [
            'PT Sumber Makmur',
            'CV Jaya Abadi',
            'PT Mitra Sejahtera',
            'UD Berkah Mandiri',
            'PT Global Supply',
            'CV Cahaya Terang',
            'PT Nusantara Trading',
            'UD Sumber Rejeki'
        ];

        // Create purchases for the last 6 months with varying patterns
        $startDate = Carbon::now()->subMonths(6);
        $endDate = Carbon::now();

        // Generate purchases for each week (less frequent than sales)
        $invoiceCounter = 1;
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addWeek()) {
            // Random purchases per week (1-3 purchases)
            $weeklyPurchasesCount = rand(1, 3);

            for ($i = 0; $i < $weeklyPurchasesCount; $i++) {
                // Random day within the week
                $purchaseDate = $date->copy()->addDays(rand(0, 6));
                $purchaseTime = $purchaseDate->copy()->addHours(rand(8, 17))->addMinutes(rand(0, 59));

                $invoiceNumber = 'PUR-' . $purchaseTime->format('Ymd') . '-' . str_pad($invoiceCounter, 4, '0', STR_PAD_LEFT);
                $invoiceCounter++;
                $paymentMethods = ['cash', 'transfer', 'credit'];
                $supplier = $suppliers[array_rand($suppliers)];

                $purchase = Purchase::create([
                    'invoice_number' => $invoiceNumber,
                    'date' => $purchaseTime->format('Y-m-d'),
                    'employee_id' => $employees->random()->id,
                    'supplier_name' => $supplier,
                    'total_amount' => '0', // Will be calculated after adding items
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'notes' => 'Purchase from ' . $supplier,
                    'created_at' => $purchaseTime,
                    'updated_at' => $purchaseTime,
                ]);

                // Add 3-8 items per purchase (restocking)
                $itemCount = rand(3, 8);
                $totalAmount = 0;

                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    $quantity = rand(10, 50); // Larger quantities for restocking
                    $unitPrice = (float) $product->purchase_price;
                    $subtotal = $quantity * $unitPrice;
                    $totalAmount += $subtotal;

                    ItemPurchase::create([
                        'purchase_id' => $purchase->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $unitPrice,
                        'amount' => $subtotal,
                        'created_at' => $purchaseTime,
                        'updated_at' => $purchaseTime,
                    ]);

                    // Update product stock (add purchased quantity)
                    $product->increment('stock', $quantity);
                }

                // Update purchase total
                $purchase->update(['total_amount' => (string) $totalAmount]);
            }
        }

        $this->command->info('Purchases seeded successfully with realistic data patterns');
    }
}

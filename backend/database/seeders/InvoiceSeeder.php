<?php

namespace Database\Seeders;

use App\Constants\DatabaseTables;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 5) as $i) {
            DB::table(DatabaseTables::INVOICES)->insert([
                'number' => 'F/' . $i,
                'buyer_nip' => fake()->numerify('##########'),
                'seller_nip' => '7582058098',
                'product_name' => fake()->randomElement(['SKP', 'FEX', 'SAM']),
                'net_amount' => fake()->randomFloat(2, 50, 1000),
                'issue_date' => now()->subDays(rand(1, 10))->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

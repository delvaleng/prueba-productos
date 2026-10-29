<?php

namespace Database\Seeders;


use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisas = [
            ['name' => 'USD', 'symbol' => '$', 'exchange_rate' => 1],
            ['name' => 'COP', 'symbol' => '$', 'exchange_rate' => 4000],
            ['name' => 'EUR', 'symbol' => '€', 'exchange_rate' => 0.92],
        ];

        foreach ($divisas as $divisa)
        {
            Currency::updateOrCreate(['name' => $divisa['name']], $divisa);
        }
    }

}

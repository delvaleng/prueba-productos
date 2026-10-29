<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario para obtener el token. La factory usa la contraseña "password".
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@prueba.test',
        ]);

        $this->call(CurrencySeeder::class);

        Product::factory(10)->create([
            'currency_id' => Currency::where('name', 'USD')->value('id'),
        ]);
    }

}

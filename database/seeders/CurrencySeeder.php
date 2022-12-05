<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::firstOrCreate([
            'iso' => 'BRL'
        ], [
            'name' => 'Real brasileiro',
            'symbol' => 'R$',
        ]);
        Currency::firstOrCreate([
            'iso' => 'USD',
        ], [
            'name' => 'Dólar americano',
            'symbol' => '$',
        ]);
    }
}

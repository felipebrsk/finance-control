<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Support\Str;
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
        Currency::create([
            'name' => $name = 'Real brasileiro',
            'slug' => Str::slug($name),
            'iso' => 'BRL',
            'symbol' => 'R$',
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Models\{Category, User, Currency};

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'weekly_report' => true,
            'first_day_of_week' => 'monday',
        ]);

        $brlCurrencyId = Currency::whereIso('BRL')->value('id');

        $space = $user->spaces()->create([
            'currency_id' => $brlCurrencyId,
            'name' => 'Espaço de Admin',
        ]);

        $space->categories()->createMany([
            [
                'name' => 'Contas',
                'color' => 'red',
            ], [
                'name' => 'Comida',
                'color' => 'purple',
            ], [
                'name' => 'Recebidos',
                'color' => 'green',
            ],
        ])->each(function (Category $category) use ($space, $brlCurrencyId) {
            if ($category->name === 'Recebidos') {
                $recurring = $category->recurrings()->create([
                    'space_id' => $space->id,
                    'currency_id' => $brlCurrencyId,
                    'description' => 'Salário',
                    'amount' => 300000,
                    'type' => 'earning',
                    'interval' => 'monthly',
                    'start_date' => Carbon::today()->toDateString(),
                ]);

                $category->earnings()->create([
                    'description' => $recurring->description,
                    'amount' => $recurring->amount,
                    'when' => Carbon::createFromDate(day: $recurring->day)->toDateString(),
                    'space_id' => $recurring->space->id,
                ]);
            }
        });
    }
}

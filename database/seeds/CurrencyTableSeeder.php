<?php

use Illuminate\Database\Seeder;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // File obtained from https://github.com/mhs/world-currencies/blob/master/currencies.json
        $filePath = storage_path('app/' . \App\Models\Currency::SEED_FILE);

        if (file_exists($filePath)) {
            // Read JSON file
            $json = file_get_contents($filePath);

            //Decode JSON
            $currencies = json_decode($json);

            foreach ($currencies as $key => $currency) {
                \App\Models\Currency::create([
                    'code'      => $currency->cc,
                    'name'      => $currency->name,
                    'symbol'    => $currency->symbol
                ]);
            }
        }
    }
}

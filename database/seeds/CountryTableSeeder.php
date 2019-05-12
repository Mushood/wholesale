<?php

use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // File obtained from https://restcountries.eu/rest/v2/all
        $filePath = storage_path('app/' . \App\Models\Country::SEED_FILE);

        if (file_exists($filePath)) {
            // Read JSON file
            $json = file_get_contents($filePath);

            //Decode JSON
            $countries = json_decode($json,true);

            foreach ($countries as $key => $country) {
                $l = \App\Models\Language::where('code', $country['languages'][0]['iso639_1'])->first();
                $c = \App\Models\Currency::where('code', $country['currencies'][0]['code'])->first();

                \App\Models\Country::create([
                    'code'          => $country['alpha2Code'],
                    'name'          => $country['name'],
                    'capital'       => $country['capital'],
                    'region'        => $country['region'],
                    'subregion'     => $country['subregion'],
                    'timezone'      => $country['timezones'][0],
                    'flag'          => $country['flag'],
                    'language_id'   => $l !== null ? $l->id : null,
                    'currency_id'   => $c !== null ? $c->id : null,
                ]);


            }
        }
    }
}
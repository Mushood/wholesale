<?php

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // File obtained from https://gist.github.com/piraveen/fafd0d984b2236e809d03a0e306c8a4d
        $filePath = storage_path('app/' . \App\Models\Language::SEED_FILE);

        if (file_exists($filePath)) {
            // Read JSON file
            $json = file_get_contents($filePath);

            //Decode JSON
            $languages = json_decode($json,true);
            foreach ($languages as $key => $language) {
                \App\Models\Language::create([
                    'code'      => $key,
                    'name'      => $language['name'],
                    'native'    => $language['nativeName']
                ]);
            }
        }
    }
}

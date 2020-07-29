<?php

use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $iteration = 0;
        $headers = [
            'name',
            'price',
            'bedrooms',
            'bathrooms',
            'storeys',
            'garages',
        ];
        $filePath = database_path('seeding_data/property-data.csv');
        if (file_exists($filePath)) {
            if (($handle = fopen($filePath, "r")) !== false) {
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($iteration > 0) {
                        /** @var array $result*/
                        $result = array_combine($headers, $row);
                        \App\House::updateOrCreate([
                            'name' => $result['name'],
                        ], $result);
                    }

                    $iteration++;
                }
                fclose($handle);
            }
        }
    }
}

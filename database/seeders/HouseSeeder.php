<?php

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $houses = [];
        $csvFile = fopen(database_path("seeders/src/houses.csv"), "r");
        $firstLine = true;

        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if ($firstLine) {
                $fields = $data;
                $firstLine = false;
                continue;
            }

            $row = [];

            foreach ($fields as $key => $field) {
                $row[strtolower($field)] = $data[$key];
            }

            $houses[] = $row;
        }

        fclose($csvFile);

        House::query()->truncate();
        House::query()->insert($houses);

    }
}

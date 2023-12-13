<?php

namespace Database\Seeders;

use App\Models\House;
use Faker\Factory;
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

            $row = ['is_main' => true];

            foreach ($fields as $key => $field) {
                $row[strtolower($field)] = $data[$key];
            }

            $houses[] = $row;
        }

        fclose($csvFile);

        House::query()->truncate();
        House::query()->insert($houses);

        $this->seedBigData(1000000);
    }
    public function seedBigData($n = 1000000): void
    {
        $houses = [];
        $faker = Factory::create();

        for ($i = 10; $i <= $n; $i++) {
            $houses[] = [
                'id' => $i,
                'name' => $faker->unique()->address,
                'price' => rand(100000, 1000000),
                'bedrooms' => rand(1, 8),
                'bathrooms' => rand(1, 5),
                'storeys' => rand(1, 4),
                'garages' => rand(1, 4),
            ];
        }

        foreach (array_chunk($houses, 1000) as $chunk) {
            House::query()->upsert($chunk, ['id']);
        }
    }
}

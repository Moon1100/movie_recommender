<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Placeholder paths to your CSV files
        $csvPathOne = database_path('seeders/csv/movies.csv');
        $csvPathTwo = database_path('seeders/csv/ratings.csv');
        $csvPathThree = database_path('seeders/csv/links.csv');
        $csvPathFour = database_path('seeders/csv/tags.csv');
        // Call the functions to import each CSV
        $this->importFirst($csvPathOne);
        $this->importSecond($csvPathTwo);
        $this->importThird($csvPathThree);
        $this->importFourth($csvPathFour);
    }

    private function importFirst($filePath)
    {
        $handle = fopen($filePath, 'r');
        fgetcsv($handle); // Skip header

        while (($data = fgetcsv($handle)) !== false) {
            DB::table('movies')->insert([
                'id' => $data[0],
                'title' => $data[1],
                'genres' => $data[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        fclose($handle);
    }

    private function importSecond($filePath)
    {
        // Placeholder logic for [placeholder_table_two]
        $handle = fopen($filePath, 'r');
        fgetcsv($handle);
        $batchData = [];
        $batchSize = 1000;

        while (($data = fgetcsv($handle)) !== false) {
            $batchData[] = [
                'user_id' => $data[0],
                'movie_id' => $data[1],
                'rating' => $data[2],
                'timestamp' => $data[3],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batchData) >= $batchSize) {
                DB::table('ratings')->insert($batchData);
                $batchData = [];
            }
        }
            
        if (!empty($batchData)) {
            DB::table('ratings')->insert($batchData);
        }
        fclose($handle);
    }

    private function importThird($filePath)
    {
        // Placeholder logic for [placeholder_table_two]
        $handle = fopen($filePath, 'r');
        fgetcsv($handle);
        $batchData = [];
        $batchSize = 1000;

        while (($data = fgetcsv($handle)) !== false) {
            $batchData[] = [
                'movie_id' => $data[0],
                'imdbId' => $data[1],
                'tmdbId' => $data[2],
                // 'timestamp' => $data[3],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batchData) >= $batchSize) {
                DB::table('links')->insert($batchData);
                $batchData = [];
            }
        }
            
        if (!empty($batchData)) {
            DB::table('links')->insert($batchData);
        }
        fclose($handle);
    }

    private function importFourth($filePath)
    {
        // Placeholder logic for [placeholder_table_two]
        $handle = fopen($filePath, 'r');
        fgetcsv($handle);
        $batchData = [];
        $batchSize = 1000;

        while (($data = fgetcsv($handle)) !== false) {
            $batchData[] = [
                'user_id' => $data[0],
                'movie_id' => $data[1],
                'tag' => $data[2],
                'timestamp' => $data[3],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batchData) >= $batchSize) {
                DB::table('tags')->insert($batchData);
                $batchData = [];
            }
        }
            
        if (!empty($batchData)) {
            DB::table('tags')->insert($batchData);
        }
        fclose($handle);
    }
}

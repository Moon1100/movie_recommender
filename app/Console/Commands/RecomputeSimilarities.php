<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\Recommendation\RecommendService;

class RecomputeSimilarities extends Command
{
    protected $recommender;

    public function __construct(RecommendService $recommender)
    {
        parent::__construct();  // Ensure the parent constructor is called
        $this->recommender = $recommender;
    }

    // Command signature
    protected $signature = 'similarities:recompute';

    // Command description
    protected $description = 'Recompute all movie similarities and store them in the cache/movie_similarities table';

    // Command execution
    public function handle()
    {
        // Log the start of the process
        $this->info('Starting similarity computations...');
        
        $count = 0;
     // Fetch all movie IDs from the database
     $movieIds = DB::table('movies')->pluck('id');

     // Loop through each movie ID and compute similarities
     foreach ($movieIds as $id) {
         $this->info("Processing movie ID: {$id}");
         $this->recommender->getSimilarMovies($id, 10);
         $count++;
     }


        // Log completion
        $this->info("Finished recomputing similarities for {$count} movies.");

        return 0;
    }
}

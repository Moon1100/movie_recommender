<?php
namespace App\Services\Recommendation;

use Illuminate\Support\Facades\DB;
use App\Models\Similarity;

class RecommendService
{

    public function getSimilarMovies(int $movieId, int $limit = 5): array
    {
        dd(Similarity::get());

        // Placeholder logic for computing movie similarities
        $userRatingsForTarget = DB::table('ratings')
            ->where('movie_id', $movieId)
            ->get();

        if ($userRatingsForTarget->isEmpty()) {
            return [];
        }
        $similarities = [];

        foreach ($userRatingsForTarget as $targetRow) {
            // Placeholder updates for similarity stats
            $otherRatings = DB::table('ratings')
            ->where('user_id', $targetRow->user_id)
            ->where('movie_id', '!=', $movieId)
            ->get();

            foreach ($otherRatings as $oRow) {
                if (!isset($similarities[$oRow->movie_id])) {
                    $similarities[$oRow->movie_id] = [
                        'common_count' => 0,
                        'sum_xy' => 0.0,
                        'sum_x2' => 0.0,
                        'sum_y2' => 0.0,
                    ];
                }

                $similarities[$oRow->movie_id]['common_count']++;
                $similarities[$oRow->movie_id]['sum_xy'] += ($targetRow->rating * $oRow->rating);

                $similarities[$oRow->movie_id]['sum_x2'] += ($targetRow->rating ** 2);

                $similarities[$oRow->movie_id]['sum_y2'] += ($oRow->rating ** 2);
            }
        }

        $results = [];
        foreach ($similarities as $otherMovieId => $stats) {

            if($stats['common_count'] < 27){
                continue;
            }

            $denominator = sqrt($stats['sum_x2']) * sqrt($stats['sum_y2']);
            $similarity = $denominator > 0
                ? $stats['sum_xy'] / $denominator
                : 0.0;

            $results[] = [
                'movie_id' => $otherMovieId,
                'similarity' => round($similarity, 4),
            ];
        }

        usort($results, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        return array_slice($results, 0, $limit);
    }
}
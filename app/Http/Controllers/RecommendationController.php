<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Recommendation\RecommendService;
use App\Models\Wishlist;
use App\Models\Similarities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    protected $recommender;

    public function __construct(RecommendService $recommender)
    {
        $this->recommender = $recommender;
    }

    public function index()
    {
        $movies = DB::table('movies')
            ->select('id', 'title')
            ->orderBy('id')
            ->limit(10)
            ->get();

        return view('recommendation.index', compact('movies'));
    }

    public function search(Request $request)
    {
        $movies = DB::table('movies')
            ->select('id', 'title')
            ->where('title','like','%'.$request->movieList.'%')
            ->orderBy('id')
            ->limit(10)
            ->get();

        return view('recommendation.search', compact('movies'));
    }

    public function getRecommendations(Request $request)
    {

        $request->validate([
            'movie_id' => 'required|integer',
        ]);

        $apiKey = env('TMDB_TOKEN');
        $baseUrl = env('TMDB_IMAGE_BASE_URL');

        

        $movieId = (int) $request->input('movie_id');
        $similarMovies = (Similarities::where('movie_id',$movieId)->exists())
            ? Similarities::where('movie_id',$movieId)
            ->select('other_movie_id as movie_id', 'rating as similarity')
            ->limit(5)->get()
            : $this->recommender->getSimilarMovies($movieId, 5);
        $currentMovie = DB::table('movies')->find($movieId);
        $recommendedMovieData = [];
        
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', "https://api.themoviedb.org/3/movie/" . $currentMovie->id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'accept' => 'application/json',
                ],
            ]);
        
            $res = json_decode($response->getBody()->getContents(), true);
        
            // Construct the poster URL or fallback to a default thumbnail image
            $currentMoviePosterUrl = isset($res['poster_path']) ? $baseUrl . 'w500' . $res['poster_path'] : asset('images/default-thumbnail.jpg');
            $currentMovieOverview=$res['overview'];
        
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error("Failed to fetch movie details: " . $e->getMessage());
        
            // Use the default thumbnail image in case of an error
            $currentMoviePosterUrl = asset('images/default-thumbnail.jpg');
            $currentMovieOverview="overview not found";

        }
        
        foreach ($similarMovies as $rec) {
            $movieInfo = DB::table('movies')->find($rec['movie_id']);
            $exists = DB::table('wishlists')->where('movie_id',$rec['movie_id'])->exists();
            
            $TMDBmovieInfo = DB::table('links')->where('movie_id',$rec['movie_id'])->first();
                
            if ($movieInfo) {

                $client = new \GuzzleHttp\Client();
                try {
                    $response = $client->request('GET', "https://api.themoviedb.org/3/movie/" . $TMDBmovieInfo->tmdbId, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $apiKey,
                            'accept' => 'application/json',
                        ],
                    ]);
                
                    $res = json_decode($response->getBody()->getContents(), true);
                    // Construct the poster URL or fallback to a default thumbnail image
                    $posterUrl = isset($res['poster_path']) ? $baseUrl . 'w500' . $res['poster_path'] : asset('images/default-thumbnail.jpg');
                
                } catch (\Exception $e) {
                    // Log the exception for debugging purposes
                    Log::error("Failed to fetch movie details: " . $e->getMessage());
                
                    // Use the default thumbnail image in case of an error
                    $posterUrl = asset('images/default-thumbnail.jpg');
                }
                
                $recommendedMovieData[] = [
                    'movie_id' => $rec['movie_id'],
                    'title' => $movieInfo->title,
                    'similarity' => $rec['similarity'],
                    'poster_url' => $posterUrl,
                    'overview' => $res['overview'],
                    'exists' => $exists
                ];
                
            }
        }

        return view('recommendation.results', [
            'currentMovie' => $currentMovie,
            'currentMoviePoster'=>$currentMoviePosterUrl,
            'currentMovieOverview'=>$currentMovieOverview,

            
            'recommendedMovies' => $recommendedMovieData,
        ]);
    }

    public function addWishlist(Request $request)
    {
        Wishlist::updateOrCreate(
            [
                'user_id' => auth()->user()->id,
                'movie_id' => data_get($request,'movie_id')
            ],
            [
                'user_id' => auth()->user()->id,
                'movie_id' => data_get($request,'movie_id')
            ]
        );

        $wishlist = DB::table('wishlists')
            ->join('users','users.id','=','wishlists.user_id')
            ->join('movies','movies.id','=','wishlists.movie_id')
            ->where('user_id',auth()->user()->id)->get();
            
        return redirect(route('wishlist.index'));
    }

    public function removeWishlist(Request $request)
    {
        $delete = DB::table('wishlists')
            ->where('user_id',auth()->user()->id)
            ->where('movie_id', data_get($request,'movie_id'))
            ->delete();

            return redirect(route('wishlist.index'));

        
    }


    public function getWishlist(Request $request)
    {

     
        $apiKey = env('TMDB_TOKEN');
        $baseUrl = env('TMDB_IMAGE_BASE_URL');
        $similarMovies=Wishlist::where('user_id',auth()->user()->id)->get();

     

  
        
        foreach ($similarMovies as $rec) {
            $movieInfo = DB::table('movies')->find($rec['movie_id']);
            $exists = DB::table('wishlists')->where('movie_id',$rec['movie_id'])->exists();
            
            $TMDBmovieInfo = DB::table('links')->where('movie_id',$rec['movie_id'])->first();
                
            if ($movieInfo) {

                $client = new \GuzzleHttp\Client();
                try {
                    $response = $client->request('GET', "https://api.themoviedb.org/3/movie/" . $TMDBmovieInfo->tmdbId, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $apiKey,
                            'accept' => 'application/json',
                        ],
                    ]);
                
                    $res = json_decode($response->getBody()->getContents(), true);
                    // Construct the poster URL or fallback to a default thumbnail image
                    $posterUrl = isset($res['poster_path']) ? $baseUrl . 'w500' . $res['poster_path'] : asset('images/default-thumbnail.jpg');
                
                } catch (\Exception $e) {
                    // Log the exception for debugging purposes
                    Log::error("Failed to fetch movie details: " . $e->getMessage());
                
                    // Use the default thumbnail image in case of an error
                    $posterUrl = asset('images/default-thumbnail.jpg');
                }
                
                $recommendedMovieData[] = [
                    'movie_id' => $rec['movie_id'],
                    'title' => $movieInfo->title,
                    'similarity' => $rec['similarity'],
                    'poster_url' => $posterUrl,
                    'overview' => $res['overview'],
                    'exists' => $exists
                ];
                
            }
        }

        return view('wishlist.index', [
        
            'wishlistMovies' => !empty($recommendedMovieData) ? $recommendedMovieData : [],
        ]);
    }
}

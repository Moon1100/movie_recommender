<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8 bg-gray-900 shadow-lg rounded-lg animate-fade-in">
        <!-- Header Section with Red and Black Gradient -->
        <div class="bg-gradient-to-r from-red-600 to-black text-white rounded-t-lg px-6 py-4">
            <h3 class="text-2xl font-bold">My Wishlist</h3>
        </div>

        <div class="p-6">

            <!-- Recommended Movies in Cards -->
            @if(count($wishlistMovies) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                    @foreach($wishlistMovies as $rMovie)
                        <div class="bg-black border border-gray-700 rounded-lg shadow-md p-4 hover:shadow-lg">
                            <!-- Movie Poster -->
                            <div class="mb-4">
                                <img src="{{$rMovie['poster_url']}}"
                                     alt="{{ $rMovie['title'] }}" 
                                     class="w-full h-56 object-cover rounded-md">
                            </div>

                            <h4 class="text-lg font-semibold text-red-600">{{ $rMovie['title'] }}</h4>
                            <p class="text-gray-400 mt-2"><span class="font-medium text-white">Movie ID:</span> {{ $rMovie['movie_id'] }}</p>
                            <p class="text-gray-400 mt-2"><span class="font-medium text-white">Similarity:</span> {{ $rMovie['similarity'] }}</p>
                            <p class="text-gray-400 mt-2"><span class="font-medium text-white">Overview:</span> {{ $rMovie['overview'] }}</p>
                            <div>
                                   <form action="{{ route('recommend.remove-wishlist') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="movie_id" value="{{ $rMovie['movie_id'] }}">
                                        <button type="submit" class="w-full bg-gray-600 text-white py-2 rounded-lg text-sm hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                                            Remove Wishlist
                                        </button>
                                    </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-lg mt-6">No recommendations could be found.</p>
            @endif

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('recommend.index') }}" 
                   class="bg-red-600 text-white py-2 px-6 rounded-lg hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                    Back to Movies
                </a>
            </div>
        </div>
    </div>

</x-app-layout>

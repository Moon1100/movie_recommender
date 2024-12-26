<x-app-layout>

    <div class="max-w-5xl mx-auto mt-8 bg-gray-900 shadow-lg rounded-lg animate-fade-in">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-800 text-white text-center rounded-t-lg py-4">
            <h3 class="text-2xl font-bold">Select a Movie to Get Recommendations</h3>
        </div>

        <!-- Body -->
        <div class="p-6">
            <!-- Search Form -->
            <form action="{{ route('recommend.search') }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-6">
                    <label for="movie_id" class="block  font-medium mb-2">Search Movie</label>
                    <input 
                        type="text" 
                        name="movieList" 
                        id="movie_id" 
                        class="w-full border border-gray-300 bg-gray-900 rounded-lg p-3 focus:outline-none focus:ring focus:ring-red-300" 
                        placeholder="Type a movie title..."
                    >
                </div>
                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                    Search
                </button>
            </form>

            <!-- Movie List -->
            <div>
                <h5 class="text-lg font-semibold text-white mb-6">Movie List:</h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($movies as $movie)
                        <div class="bg-gray-700 rounded-lg shadow hover:shadow-md transition-shadow duration-300">
                            <div class="p-4">
                                <h4 class="text-white font-bold text-lg truncate">{{ $movie->title }}</h4>
                            </div>
                            <div class="px-4 pb-4 space-y-2">
                                <form action="{{ route('recommend.results') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <button type="submit" class="w-full bg-red-800 text-white py-2 rounded-lg text-sm hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                                        Get Recommendations
                                    </button>
                                </form>

                                <form action="{{ route('recommend.wishlist') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg text-sm hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                                        Wishlist
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


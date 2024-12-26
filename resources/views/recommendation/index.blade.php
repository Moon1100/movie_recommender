<x-app-layout>

    <div class="max-w-3xl mx-auto mt-10 animate-fade-in">
        <!-- Container with fade-in animation -->
        <div class="bg-gray-800 shadow-md rounded-lg animate-fade-in">
            <div class="bg-gradient-to-r from-red-600 to-red-800 text-white rounded-t-lg px-6 py-4">
                <h3 class="text-xl font-semibold">Select a Movie to Get Recommendations</h3>
            </div>

            <div class="px-6 py-4">
                <form action="{{ route('recommend.search') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="movie_id" class="block text-white font-medium mb-2">Movie Title</label>
                        <input 
                            type="text" 
                            name="movieList" 
                            class="w-full bg-gray-900 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="Type a movie title..."
                        >
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-2 px-4 rounded-lg hover:bg-gradient-to-r hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Search
                    </button>
                </form>
            </div>
        </div>

        <!-- Movie list with fade-in animation -->
        <div class="mt-6">
            <div class="bg-gray-800 shadow-md rounded-lg animate-fade-in">
                <div class="px-6 py-4">
                    <h4 class="text-lg font-medium text-white mb-4">Movie List</h4>
                    <div class="space-y-4">
                        @foreach($movies as $movie)
                            <div class="flex items-center justify-between px-4 py-3 rounded-lg bg-gray-800 hover:shadow-lg animate-fade-in">
                                <span class=" font-medium text-white">{{ $movie->title }}</span>
                                <form action="{{ route('recommend.search') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                                    <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 text-white py-1 px-3 rounded-lg hover:bg-gradient-to-r hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Get Recommendations
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

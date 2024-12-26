<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-in-out;
        }
    </style>
</head>

<body class="bg-gray-700 text-white">
    <!-- Navigation Bar -->
    <nav class="bg-gray-900 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center px-4 py-3">
            <a href="#" class="text-lg font-bold">Movie Recommender</a>
            <button class="text-gray-400 hover:text-white focus:outline-none focus:ring focus:ring-gray-500 md:hidden">
                <!-- Mobile Menu Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto mt-6 px-4 animate-fade-in">
        @yield('content')
    </div>
</body>

</html>

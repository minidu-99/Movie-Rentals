<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Movies - Movie Rentals Lk</title>
    <link rel="stylesheet" href="css/styles_browse.css">
    <script src="app.js" defer></script> <!-- Link to your app.js file -->
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <h1>Movie Rentals Lk</h1>
            </div>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search for movies...">
                <button class="btn-search">Search</button>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="movies.php">Movies</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li class="dropdown">
                            <a href="#" class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                            <ul class="dropdown-content">
                                <li><a href="account.php">My Account</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="container">
            <div class="filters-container">
                <div class="filters">
                    <div class="filter">
                        <label for="genre">Genre</label>
                        <select id="genre">
                            <option value="all">All</option>
                            <option value="action">Action</option>
                            <option value="drama">Drama</option>
                            <option value="sci-fi">Sci-Fi</option>
                            <option value="adventure">Adventure</option>
                            <option value="animatiom">Animatiom</option>
                        </select>
                    </div>
                    <div class="filter">
                        <label for="year">Year</label>
                        <select id="year">
                            <option value="all">All</option>
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                            <option value="2022">2022</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                        </select>
                    </div>
                    <div class="filter">
                        <label for="rating">Rating</label>
                        <select id="rating">
                            <option value="all">All</option>
                            <option value="2">2+</option>
                            <option value="3">3+</option>
                            <option value="4">4+</option>
                            <option value="5">5+</option>
                            <option value="6">6+</option>
                            <option value="7">7+</option>
                            <option value="8">8+</option>
                            <option value="9">9+</option>
                        </select>
                    </div>
                    <button class="btn-filter" id="filterButton">Filter</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Movies Grid -->
    <section class="movies-section">
        <div class="container">
            <div id="moviesGrid" class="movies-grid">
                <!-- Movie cards will be injected here by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Movie Rentals Lk. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const moviesGrid = document.getElementById('moviesGrid');

            // Function to display movies
            function displayMovies(movies) {
                moviesGrid.innerHTML = ''; // Clear existing movies

                if (movies.length === 0) {
                    moviesGrid.innerHTML = '<p>No movies found matching your criteria.</p>';
                    return;
                }

                movies.forEach(movie => {
                    const movieCard = `
                        
                            
                            <a href="movie.php?id=${encodeURIComponent(movie.id)}" class="movie-card"> <!-- Adding link with movie ID -->
                                <img src="../Admin/${movie.image}" alt="${movie.title}">
                                <h3>${movie.title}</h3>
                                <p>${movie.genre}, ${movie.releaseYear}</p>
                                <p>IMDb: <span class="rating">${movie.rating}</span></p>
                            </a>  
                       
                    `;
                    moviesGrid.innerHTML += movieCard;
                });
            }

            // Function to load movies from XML
            function loadMovies() {
                fetch('load_movies.php')
                    .then(response => response.json())
                    .then(movies => {
                        displayMovies(movies);
                    })
                    .catch(error => console.error('Error loading movies:', error));
            }

            // Load movies on page load
            loadMovies();

            // Filter functionality
            document.getElementById('filterButton').addEventListener('click', () => {
                const genre = document.getElementById('genre').value;
                const year = document.getElementById('year').value;
                const rating = document.getElementById('rating').value;

                fetch('load_movies.php')
                    .then(response => response.json())
                    .then(movies => {
                        const filteredMovies = movies.filter(movie => {
                            const matchesGenre = genre === 'all' || movie.genre.toLowerCase() === genre.toLowerCase();
                            const matchesYear = year === 'all' || movie.releaseYear === year;
                            const matchesRating = rating === 'all' || movie.rating >= parseFloat(rating);
                            return matchesGenre && matchesYear && matchesRating;
                        });

                        displayMovies(filteredMovies);
                    })
                    .catch(error => console.error('Error loading movies:', error));
            });

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', () => {
                const query = document.getElementById('searchInput').value.toLowerCase();
                fetch('load_movies.php')
                    .then(response => response.json())
                    .then(movies => {
                        const searchedMovies = movies.filter(movie => movie.title.toLowerCase().includes(query));
                        displayMovies(searchedMovies);
                    })
                    .catch(error => console.error('Error loading movies:', error));
            });
        });
    </script>

</body>
</html>

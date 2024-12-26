<?php
session_start(); // Start the session

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['username']);

// Get movie ID from URL and treat it as a string
$movieId = isset($_GET['id']) ? (string) $_GET['id'] : null;

// Load the XML file
$xml = simplexml_load_file('xml/movies.xml');

// Check if XML loaded correctly
if ($xml === false) {
    die("Error loading XML file");
}

// Initialize movie data
$movieData = null;

// Debugging: Output the movie ID
// echo "Searching for movie ID: " . htmlspecialchars($movieId) . "<br>";

// Search for the movie with the matching ID
foreach ($xml->movie as $movie) {
    // Access the id attribute correctly
    $currentMovieId = (string) $movie['id']; // Accessing ID as an attribute
    
    // Debugging: Output each movie ID for comparison
    // echo "Comparing with movie ID: " . htmlspecialchars($currentMovieId) . "<br>";
    
    if ($currentMovieId === $movieId) { // Strict comparison as strings
        $movieData = $movie;
        break;
    }
}

// If no movie is found, show an error and exit
if (!$movieData) {
    echo "<p>Movie not found!</p>";
    exit; // Use exit to prevent further processing
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movieData->title); ?> - Movie Rentals Lk</title>
    <link rel="stylesheet" href="css/styles_movie.css">
</head>
<body>

    <!-- Header (NAV) -->
    <header>
        <div class="container header-container">
            <div class="logo">
                <h1>Movie Rentals Lk</h1>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Search for movies...">
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

    <!-- Movie Rental Section -->
    <div class="container movie-rental-container">
        <div class="movie-poster">
            <!-- Movie poster image -->
            <img id="moviePoster" src="../Admin/<?php echo htmlspecialchars($movieData->image); ?>" alt="<?php echo htmlspecialchars($movieData->title); ?>" />
        </div>
        <div class="movie-info">
            <h2 id="movieTitle"><?php echo htmlspecialchars($movieData->title); ?></h2>
            <p id="movieDescription"><?php echo htmlspecialchars($movieData->description); ?></p>
            <p><strong>Genre: </strong><?php echo htmlspecialchars($movieData->genre); ?></p>
            <p><strong>Release Year: </strong><?php echo htmlspecialchars($movieData->{"release-year"}); ?></p>
            <p><strong>IMDb Rating: </strong><span id="imdbRating"><?php echo htmlspecialchars($movieData->rating); ?></span>/10</p>
            

            <?php if ($isLoggedIn): ?>
                <button id="rentMovieButton" class="btn-rent">Rent this Movie</button>
            <?php else: ?>
                <p><a href="login.php" class="btn-login-to-rent">Log in to Rent</a></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Movie Rentals Lk. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Simulate rent button click event
        // Simulate rent button click event
    document.getElementById('rentMovieButton')?.addEventListener('click', function() {
        const movieId = "<?php echo htmlspecialchars($movieData['id']); ?>"; // Get movie ID
        const movieTitle = "<?php echo htmlspecialchars($movieData->title); ?>"; // Get movie title
        
        // AJAX request to rent the movie
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'rent_movie.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText); // Show response message
                } else {
                    alert('Error renting the movie!');
                }
            }
        };

        xhr.send('movieId=' + movieId + '&movieTitle=' + encodeURIComponent(movieTitle));
    });
    </script>

</body>
</html>

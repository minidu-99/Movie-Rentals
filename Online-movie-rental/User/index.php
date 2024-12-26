<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Rentals Lk - Home</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
    session_start(); // Start the session

    // Check if the user is logged in
    $isLoggedIn = isset($_SESSION['username']);

    // Load XML file
    $moviesXml = simplexml_load_file('xml/movies.xml');
    if ($moviesXml === false) {
        die('Error loading XML file');
    }

    // Initialize arrays for featured and trending movies
    $featuredMovies = [];
    $trendingMovies = [];

    // Loop through each movie in the XML
    foreach ($moviesXml->movie as $movie) {
        if (strtolower($movie->featured) === 'yes') {
            $featuredMovies[] = $movie;
        }
        if (strtolower($movie->trending) === 'yes') {
            $trendingMovies[] = $movie;
        }
    }
    ?>

    <!-- Hero Section with Embedded Logo and Navbar -->
    <section class="hero">
        <div class="hero-nav">
            <div class="logo">
                <h1>Movie Rentals Lk</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="movies.php">Movies</a></li>

                    <!-- Conditional rendering based on session -->
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

        <div class="container">
            <h2>Unlimited Movies, Anytime, Anywhere</h2>
            <p>Browse, rent, and enjoy the latest movies at Movie Rentals Lk.</p>
            <div class="cta">
                <a href="movies.php" class="btn">Browse Movies</a>

                <!-- Only show the 'Register Now' button if the user is NOT logged in -->
                <?php if (!$isLoggedIn): ?>
                    <a href="register.php" class="btn secondary">Register Now</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    

    <!-- Featured Movies Section -->
    <section class="featured">
        <div class="container">
            <h3>Featured Movies</h3>
            <div class="movie-grid">
                <?php foreach ($featuredMovies as $movie): ?>
                    <a href="movie.php?id=<?php echo htmlspecialchars($movie['id']); ?>" class="movie-card">
                        <img src="../Admin/<?php echo htmlspecialchars($movie->image); ?>" alt="<?php echo htmlspecialchars($movie->title); ?>">
                        <h4><?php echo htmlspecialchars($movie->title); ?></h4>
                        <p><?php echo htmlspecialchars($movie->genre) . ', ' . htmlspecialchars($movie->{"release-year"}); ?></p>
                        <p>IMDb : <span class="rating"><?php echo htmlspecialchars($movie->rating); ?></span></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Trending Movies Section -->
    <section class="trending">
        <div class="container">
            <h3>Trending Movies</h3>
            <div class="movie-grid">
                <?php foreach ($trendingMovies as $movie): ?>
                    <a href="movie.php?id=<?php echo htmlspecialchars($movie['id']); ?>" class="movie-card">
                        <img src="../Admin/<?php echo htmlspecialchars($movie->image); ?>" alt="<?php echo htmlspecialchars($movie->title); ?>">
                        <h4><?php echo htmlspecialchars($movie->title); ?></h4>
                        <p><?php echo htmlspecialchars($movie->genre) . ', ' . htmlspecialchars($movie->{"release-year"}); ?></p>
                        <p>IMDb : <span class="rating"><?php echo htmlspecialchars($movie->rating); ?></span></p>
                    </a>
                <?php endforeach; ?>
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
        function searchMovies() {
            let query = document.getElementById('searchInput').value;
            alert('You searched for: ' + query);
        }
    </script>
</body>
</html>

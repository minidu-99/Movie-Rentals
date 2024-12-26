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
    <title>My Account - Movie Rentals Lk</title>
    <link rel="stylesheet" href="css/styles_account.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load currently rented movies on page load
            loadContent('currently-rented');

            // Load content based on sidebar clicks
            $('.sidebar a').click(function(event) {
                event.preventDefault(); // Prevent default link behavior
                const target = $(this).attr('href').substring(1); // Get the target from href

                loadContent(target);
            });

            function loadContent(target) {
                // Clear current content
                $('.account-content').html(' ');

                // Load the appropriate content via AJAX
                $.ajax({
                    url: target + '.php', // AJAX request to target PHP file
                    method: 'GET',
                    success: function(data) {
                        $('.account-content').html(data); // Load data into account content
                    },
                    error: function() {
                        $('.account-content').html('<h2>Error loading content</h2>'); // Error handling
                    }
                });
            }
        });
    </script>
</head>
<body>

    <!-- Header -->
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
                                <li><a href="user.php">My Account</a></li>
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

    <div class="container account-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <h2>Account Menu</h2>
            <ul>
                <li><a href="#currently-rented">Currently Rented</a></li>
                <li><a href="#rental-history">Rental History</a></li>
            </ul>
        </aside>

        <!-- Account Section -->
        <main class="account-content">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <h3>Loading...</h3>
        </main>
    </div>

    <!-- Footer -->
    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Movie Rentals Lk. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>

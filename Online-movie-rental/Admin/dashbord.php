<?php
session_start();

// Load the users XML file
$userXmlFile = '../User/xml/users.xml'; // Adjust the path to your XML file

// Check if the file exists
if (!file_exists($userXmlFile)) {
    echo "Error: The XML file does not exist.";
    exit;
}

// Load the XML file
$xml = simplexml_load_file($userXmlFile);

// Check if the XML loaded correctly
if ($xml === false) {
    echo "Error loading user XML file.";
    foreach (libxml_get_errors() as $error) {
        echo "<p>Error: {$error->message}</p>";
    }
    exit;
}

// Initialize an array to hold orders
$orders = [];

// Loop through each user to get the rental details
foreach ($xml->user as $user) {
    foreach ($user->rentals->rental as $rental) {
        // Check if returnStatus is 'no'
        if ((string)$rental->returnStatus === 'no') {
            $orders[] = [
                'username' => (string)$user->username,
                'movieTitle' => (string)$rental->movieTitle,
                'rentalId' => (string)$rental->movieId, // Assuming movieId is unique for the rental
                'rentedOn' => (string)$rental->rentedOn,
                'returnDate' => (string)$rental->returnDate,
            ];
        }
    }
}

// Handle changing return status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rentalId'])) {
    $rentalId = $_POST['rentalId'];

    // Update the XML to change return status
    foreach ($xml->user as $user) {
        foreach ($user->rentals->rental as $rental) {
            if ((string)$rental->movieId === $rentalId) {
                $rental->returnStatus = 'yes'; // Change return status to yes
                break 2; // Break out of both loops
            }
        }
    }

    // Save the updated XML back to the file
    $xml->asXML($userXmlFile);
    header('Location: ' . $_SERVER['PHP_SELF']); // Redirect to refresh the page
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Movie Rentals Lk</title>
    <link rel="stylesheet" href="../User/css/admin_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Admin Dashboard</h2>
            <nav>
                <ul>
                    <li><a href="#" id="statsTab">Statistics</a></li>
                    <li><a href="#" id="moviesTab">Movies</a></li>
                    <li><a href="#" id="ordersTab">Orders</a></li>
                    <li><a href="#" id="adminsTab">Add Admins</a></li>
                    <li><a href="../User/logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Sections -->
            <section class="card" id="movieManagementSection" style="display: none;">
                <h2>Add Movies</h2>
                <form id="addMovieForm">
                    <div class="form-row">
                        <label for="movieTitle">Title:</label>
                        <input type="text" id="movieTitle" name="movieTitle" required>
                    </div>

                    <div class="form-row">
                        <label for="movieGenre">Genre:</label>
                        <input type="text" id="movieGenre" name="movieGenre" required>
                    </div>

                    <div class="form-row">
                        <label for="movieDescription">Description:</label>
                        <textarea id="movieDescription" name="movieDescription" required></textarea>
                    </div>

                    <div class="form-row compact">
                        <div class="half">
                            <label for="movieCopies">Number of Copies:</label>
                            <input type="number" id="movieCopies" name="movieCopies" min="1" required>
                        </div>
                        <div class="half">
                            <label for="movieYear">Release Year:</label>
                            <input type="number" id="movieYear" name="movieYear" min="1900" required>
                        </div>
                        <div class="half">
                            <label for="movieRating">Rating:</label>
                            <input type="number" id="movieRating" name="movieRating" min="1" max="10" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="movieImage">Image:</label>
                        <input type="file" id="movieImage" name="movieImage" accept="image/*" required>
                        <img id="imagePreview" style="display:none; max-width: 200px; margin-top: 10px;" alt="Image Preview">
                    </div>

                    <div class="form-row">
                        <label for="movieAvailability">Available:</label>
                        <input type="checkbox" id="movieAvailability" name="movieAvailability">
                    </div>

                    <div class="form-row">
                        <label for="movieTrending">Trending:</label>
                        <input type="checkbox" id="movieTrending" name="movieTrending">
                    </div>

                    <div class="form-row">
                        <label for="movieFeatured">Feature:</label>
                        <input type="checkbox" id="movieFeatured" name="movieFeatured">
                    </div>

                    <button type="submit">Add Movie</button>
                </form>
                <p id="addMovieResponse"></p> <!-- Response message -->
            </section>

            <section class="card" id="orderManagementSection">
                <h2>Manage Orders</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Movie Title</th>
                            <th>Rented On</th>
                            <th>Return Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                                    <td><?php echo htmlspecialchars($order['movieTitle']); ?></td>
                                    <td><?php echo htmlspecialchars($order['rentedOn']); ?></td>
                                    <td><?php echo htmlspecialchars($order['returnDate']); ?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="rentalId" value="<?php echo htmlspecialchars($order['rentalId']); ?>">
                                            <button type="submit">Mark as Returned</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <section class="card" id="addAdminSection">
                <h2>Add New Admin</h2>
                <form id="addAdminForm">
                    <div class="form-row">
                        <label for="adminUsername">Username:</label>
                        <input type="text" id="adminUsername" name="adminUsername" required>
                    </div>
                    <div class="form-row">
                        <label for="adminEmail">Email:</label>
                        <input type="email" id="adminEmail" name="adminEmail" required>
                    </div>
                    <div class="form-row">
                        <label for="adminPassword">Password:</label>
                        <input type="password" id="adminPassword" name="adminPassword" required>
                    </div>
                    <div class="form-row">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                    </div>
                    <button type="submit">Add Admin</button>
                </form>
                <p id="addAdminResponse"></p>
            </section>

            <!-- Statistics Section -->
            <section class="card" id="statsSection" style="display: none;">
                <header>
                    <h1>Welcome to the Statistics Dashboard</h1>
                    <div class="statistics">
                        <div class="stat-item">
                            <h2 id="totalMoviesCount">0</h2>
                            <p>Total Movies</p>
                        </div>
                        <div class="stat-item">
                            <h2 id="totalCopiesCount">0</h2>
                            <p>Total Copies</p>
                        </div>
                        
                    </div>
                </header>

                <h2>All Movies</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Description</th>
                            <th>Copies</th>
                            <th>Year</th>
                            <th>Rating</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="moviesTableBody">
                        <!-- Movies will be populated here -->
                    </tbody>
                </table>
            </section>
        </main>
    </div>
    
    <script>
        // Show relevant sections based on clicked tab
        document.getElementById('statsTab').onclick = function() {
            document.getElementById('movieManagementSection').style.display = 'none';
            document.getElementById('orderManagementSection').style.display = 'none';
            document.getElementById('addAdminSection').style.display = 'none';
            document.getElementById('statsSection').style.display = 'block';
        };
        document.getElementById('moviesTab').onclick = function() {
            document.getElementById('movieManagementSection').style.display = 'block';
            document.getElementById('orderManagementSection').style.display = 'none';
            document.getElementById('addAdminSection').style.display = 'none';
            document.getElementById('statsSection').style.display = 'none';
        };
        document.getElementById('ordersTab').onclick = function() {
            document.getElementById('movieManagementSection').style.display = 'none';
            document.getElementById('orderManagementSection').style.display = 'block';
            document.getElementById('addAdminSection').style.display = 'none';
            document.getElementById('statsSection').style.display = 'none';
        };
        document.getElementById('adminsTab').onclick = function() {
            document.getElementById('movieManagementSection').style.display = 'none';
            document.getElementById('orderManagementSection').style.display = 'none';
            document.getElementById('addAdminSection').style.display = 'block';
            document.getElementById('statsSection').style.display = 'none';
        };

        // Image preview for movie upload
        document.getElementById('movieImage').onchange = function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('imagePreview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        };
    </script>
    <script src="../User/js/app.js"></script>
    <script src="../User/js/auth.js"></script>
</body>
</html>

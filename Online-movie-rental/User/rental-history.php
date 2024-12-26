<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You must be logged in to view rental history.";
    exit;
}

// Load the user XML file
$userXmlFile = 'xml/users.xml'; // Path to your user XML file
$xml = simplexml_load_file($userXmlFile);

// Check if the XML loaded correctly
if ($xml === false) {
    echo "Error loading user XML file.";
    exit;
}

// Find the current user
$username = $_SESSION['username'];
$rentalHistory = []; // Initialize an array to hold rental history data

foreach ($xml->user as $user) {
    if ((string)$user->username === $username) {
        // Loop through the rentals to find movies with returnStatus 'yes'
        foreach ($user->rentals->rental as $rental) {
            if ((string)$rental->returnStatus === 'yes') {
                // Add relevant rental history data to the array
                $rentalHistory[] = [
                    'movie_name' => (string)$rental->movieTitle,
                    'rental_date' => (string)$rental->rentedOn,
                    'return_date' => (string)$rental->returnDate,
                ];
            }
        }
        break; // Exit the loop once the user is found
    }
}

// Output the rental history table
echo '<h3>Rental History</h3>';
echo '<table>';
echo '<thead><tr><th>Movie Name</th><th>Rental Date</th><th>Return Date</th></tr></thead>';
echo '<tbody>';
foreach ($rentalHistory as $history) {
    echo '<tr><td>' . htmlspecialchars($history['movie_name']) . '</td><td>' . htmlspecialchars($history['rental_date']) . '</td><td>' . htmlspecialchars($history['return_date']) . '</td></tr>';
}
echo '</tbody></table>';
?>

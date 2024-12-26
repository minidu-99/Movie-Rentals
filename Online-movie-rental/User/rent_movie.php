<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "You must be logged in to rent a movie.";
    exit;
}

// Get movie details from AJAX request
$movieId = isset($_POST['movieId']) ? $_POST['movieId'] : null;
$movieTitle = isset($_POST['movieTitle']) ? $_POST['movieTitle'] : null;

// Calculate return date (7 days from today)
$returnDate = date('Y-m-d', strtotime('+7 days'));

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
$userFound = false;

foreach ($xml->user as $user) {
    if ((string)$user->username === $username) {
        // Add the rented movie to the user's rentals
        $rental = $user->rentals->addChild('rental');
        $rental->addChild('movieId', htmlspecialchars($movieId));
        $rental->addChild('movieTitle', htmlspecialchars($movieTitle));
        $rental->addChild('rentedOn', date('Y-m-d')); // Current date
        $rental->addChild('returnDate', $returnDate); // Return date
        $rental->addChild('returnStatus', 'no');

        // Save the updated XML
        $xml->asXML($userXmlFile);
        $userFound = true;
        break;
    }
}

// Provide feedback based on whether the user was found
if ($userFound) {
    echo "You have successfully rented \"$movieTitle\"! Return by: $returnDate.";
} else {
    echo "User not found.";
}
?>

<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the movie details
    $title = $_POST['movieTitle'];
    $genre = $_POST['movieGenre'];
    $description = $_POST['movieDescription'];
    $copies = $_POST['movieCopies'];
    $year = $_POST['movieYear'];
    $rating = $_POST['movieRating'];
    $availability = isset($_POST['movieAvailability']) ? 'yes' : 'no'; // Check availability
    $trending = isset($_POST['movieTrending']) ? 'yes' : 'no';
    $feature = isset($_POST['movieFeatured']) ? 'yes' : 'no';


    // Handle the file upload
    if (isset($_FILES['movieImage']) && $_FILES['movieImage']['error'] == 0) {
        $imageName = $_FILES['movieImage']['name'];
        $imageTmpName = $_FILES['movieImage']['tmp_name'];
        $uploadDir = 'uploads/'; // Make sure this directory exists
        $imagePath = $uploadDir . basename($imageName);

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Load the existing XML file or create a new one
            $xmlFile = '../User/xml/movies.xml';
            if (file_exists($xmlFile)) {
                $xml = simplexml_load_file($xmlFile);

                // Get the highest ID value and increment it for the new movie
                $highestId = 0;
                foreach ($xml->movie as $movie) {
                    $currentId = (int)$movie['id'];
                    if ($currentId > $highestId) {
                        $highestId = $currentId;
                    }
                }
                $newId = $highestId + 1; // Generate new unique ID
            } else {
                $xml = new SimpleXMLElement('<movies></movies>');
                $newId = 1; // First movie gets ID 1
            }

            // Create a new movie element with the unique ID
            $movie = $xml->addChild('movie');
            $movie->addAttribute('id', $newId); // Set the unique ID attribute
            $movie->addChild('title', htmlspecialchars($title));
            $movie->addChild('genre', htmlspecialchars($genre));
            $movie->addChild('description', htmlspecialchars($description));
            $movie->addChild('copies', htmlspecialchars($copies));
            $movie->addChild('release-year', htmlspecialchars($year));
            $movie->addChild('rating', htmlspecialchars($rating));
            $movie->addChild('availability', htmlspecialchars($availability)); // Add availability
            $movie->addChild('trending', htmlspecialchars($trending)); // Add availability
            $movie->addChild('featured', htmlspecialchars($feature)); // Add availability
            $movie->addChild('image', htmlspecialchars($imagePath));

            // Save the XML file
            if ($xml->asXML($xmlFile)) {
                $response['success'] = true;
                $response['message'] = 'Movie added successfully!';
            } else {
                $response['message'] = 'Failed to save movie data.';
            }
        } else {
            $response['message'] = 'Failed to upload image.';
        }
    } else {
        $response['message'] = 'Invalid image file.';
    }
}

// Return the JSON response
echo json_encode($response);
?>

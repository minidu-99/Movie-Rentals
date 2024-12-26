<?php
header('Content-Type: application/json');

// Load the XML file
$xml = simplexml_load_file('xml/movies.xml');

$movies = [];

// Loop through each movie in the XML
foreach ($xml->movie as $movie) {
    if ((string)$movie->availability === 'yes') { // Check availability
        $movies[] = [
            'id' => (int)$movie['id'],
            'title' => (string)$movie->title,
            'genre' => (string)$movie->genre,
            'description' => (string)$movie->description,
            'copies' => (int)$movie->copies,
            'releaseYear' => (int)$movie->{'release-year'},
            'rating' => (float)$movie->rating,
            'image' => (string)$movie->image
        ];
    }
}

// Return movies as JSON
echo json_encode($movies);
?>

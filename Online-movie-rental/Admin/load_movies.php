<?php
header('Content-Type: application/json');

$moviesFile = '../User/xml/movies.xml';

if (file_exists($moviesFile)) {
    $xml = simplexml_load_file($moviesFile);
    $movies = [];

    foreach ($xml->movie as $movie) {
        $movies[] = [
            'id' => (string)$movie['id'],
            'title' => (string)$movie->title,
            'genre' => (string)$movie->genre,
            'description' => (string)$movie->description,
            'copies' => (int)$movie->copies,
            'year' => (int)$movie->{'release-year'},
            'rating' => (float)$movie->rating,
            'availability' => (string)$movie->availability,
            'image' => (string)$movie->image,
        ];
    }

    echo json_encode($movies);
} else {
    echo json_encode([]);
}
?>

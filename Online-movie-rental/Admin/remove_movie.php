<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $movieId = $input['id'];

    $xmlFile = '../User/xml/movies.xml';
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
        $movieToRemove = null;

        // Find the movie by ID
        foreach ($xml->movie as $movie) {
            if ((string)$movie['id'] === (string)$movieId) {
                $movieToRemove = $movie;
                break;
            }
        }

        if ($movieToRemove) {
            $dom = dom_import_simplexml($movieToRemove);
            $dom->parentNode->removeChild($dom); // Remove the movie node
            $xml->asXML($xmlFile); // Save the updated XML

            $response['success'] = true;
            $response['message'] = 'Movie removed successfully!';
        } else {
            $response['message'] = 'Movie not found.';
        }
    } else {
        $response['message'] = 'Movies file not found.';
    }
}

echo json_encode($response);
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['adminUsername'];
    $email = $_POST['adminEmail'];
    $password = $_POST['adminPassword'];

    // Load the XML file or create it if it doesn't exist
    $filePath = '../User/xml/users.xml';
    if (file_exists($filePath)) {
        $xml = simplexml_load_file($filePath);
    } else {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><users></users>');
    }

    // Find the highest existing ID and increment it for the new user
    $maxId = 0;
    foreach ($xml->user as $user) {
        $id = (int)$user['id'];
        if ($id > $maxId) {
            $maxId = $id;
        }
    }
    $newId = $maxId + 1;

    // Create a new user node
    $newUser = $xml->addChild('user');
    $newUser->addAttribute('id', $newId); // Assign auto-incrementing ID
    $newUser->addChild('username', htmlspecialchars($username));
    $newUser->addChild('password', password_hash($password, PASSWORD_DEFAULT)); // Hash the password
    $newUser->addChild('email', htmlspecialchars($email)); // Add email
    $newUser->addChild('rentals', ''); // Empty rentals section for admin
    $newUser->addChild('role', 'admin'); // Set role as admin

    // Save the updated XML back to the file
    $xml->asXML($filePath);

    // Return a success message as JSON
    echo json_encode(['success' => true, 'message' => 'Admin added successfully with ID: ' . $newId]);
    exit();
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Path to users.xml file
    $xmlFile = 'xml/users.xml';

    // Load the existing users.xml file or create a new XML document if it doesn't exist
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<users></users>'); // Create a root <users> element
    }

    // Auto-generate a new unique user ID (find the highest ID and increment it)
    $lastUser = $xml->xpath('/users/user[last()]');  // Find the last <user> in the file
    if (!empty($lastUser)) {
        $lastId = (int) $lastUser[0]->attributes()->id;  // Get the ID of the last user
        $newId = $lastId + 1;  // Increment the ID
    } else {
        $newId = 1;  // If no users exist yet, start with ID 1
    }

    // Create a new <user> element
    $newUser = $xml->addChild('user');
    $newUser->addAttribute('id', $newId);

    // Add username and hashed password
    $newUser->addChild('username', $username);
    $newUser->addChild('password', password_hash($password, PASSWORD_DEFAULT));  // Hash the password

    // Add an empty <rentals> section
    $newUser->addChild('rentals', ' ');  // Empty <rentals> tag

    // Add a role tag and set it to "user"
    $newUser->addChild('role', 'user');  // New <role> tag with the value "user"

    // Save the updated XML back to users.xml
    $xml->asXML($xmlFile);

    // Send a success response to the client
    echo "Registration successful!";
}
?>

<?php
session_start();

// Function to load users from XML file
function loadUsers($filename) {
    $xml = simplexml_load_file($filename);
    $users = [];
    
    foreach ($xml->user as $user) {
        $users[(string)$user->username] = [
            'password' => (string)$user->password,
            'role' => (string)$user->role
        ];
    }
    
    return $users;
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']); // Use 'username'
    $password = trim($_POST['password']);

    // Path to users.xml file
    $xmlFile = 'xml/users.xml';

    // Load users from XML
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        header('Location: login.php?message=' . urlencode('Invalid username or password.'));
        exit;
    }

    $userFound = false;
    $userRole = '';

    // Iterate through users to find a match
    foreach ($xml->user as $user) {
        // Check if the username matches
        if ((string)$user->username === $username) {
            $userFound = true;
            $userRole = (string)$user->role; // Fetch role (admin/user)
            // Verify the password against the hashed password
            if (password_verify($password, (string)$user->password)) {
                $_SESSION['username'] = $username; // Set session variable for logged-in user
                $_SESSION['role'] = $userRole;     // Set role in session

                // Redirect based on role
                if ($userRole === 'admin') {
                    header('Location: ../Admin/dashbord.php'); // Redirect to admin dashboard
                } else {
                    header('Location: index.php'); // Redirect to user page
                }
                exit;
            } else {
                // Password does not match
                break; // Exit loop if password is incorrect
            }
        }
    }

    if (!$userFound || !password_verify($password, (string)$user->password)) {
        // Username not found or password does not match
        header('Location: login.php?message=' . urlencode('Invalid username or password.'));
        exit;
    }
}
?>

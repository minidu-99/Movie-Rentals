<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="css/styles_login.css">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="header-container container">
            <div class="logo">
                <h1>Movie Rentals Lk</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="movies.php">Movies</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Login Section -->
    <section class="login-section">
        <div class="container">
            <!-- Message box (hidden by default) -->
            <div id="loginMessage" class="message-box message-hidden">
                <span id="messageText"></span>
                <button class="close-btn" onclick="closeMessage()">×</button>
            </div>
            <h2>Login to Your Account</h2>

            <form class="login-form" method="POST" action="login_logic.php">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
                <p class="register-link">Don't have an account? <a href="register.html">Register here</a></p>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Movie Rentals Lk. All rights reserved.</p>
    </footer>
    
    <script>
        // Function to display success message from query string
        function displayMessage() {
            const params = new URLSearchParams(window.location.search);
            const message = params.get('message');
            if (message) {
                const messageBox = document.getElementById('loginMessage');
                const messageText = document.getElementById('messageText');
                messageText.textContent = message;
                messageBox.classList.remove('message-hidden'); // Show the message box
            }
        }

        // Function to close the message box
        function closeMessage() {
            const messageBox = document.getElementById('loginMessage');
            messageBox.classList.add('message-hidden'); // Hide the message box
        }

        // Run the function when the page loads
        document.addEventListener('DOMContentLoaded', displayMessage);
    </script>
</body>
</html>

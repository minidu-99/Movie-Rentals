<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/styles_register.css">
    <style>
        /* Message Box Styles */
        .message-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #f44336; /* Red background for error */
            color: white;
            border-radius: 5px;
            margin-bottom: 20px;
            position: relative;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .message-box .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            padding: 0 10px;
        }

        .message-box .close-btn:hover {
            color: #fff;
        }

        .message-hidden {
            opacity: 0;
            display: none;
        }
    </style>
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

    <!-- Registration Section -->
    <section class="register-section">
        <div class="container">

            <!-- Message box (hidden by default) -->
            <div id="registerMessage" class="message-box message-hidden">
                <span id="messageText"></span>
                <button class="close-btn" onclick="closeMessage()">×</button>
            </div>

            <h2>Create an Account</h2>
            <form id="registerForm" class="register-form">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="btn-register">Register</button>
                <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Movie Rentals Lk. All rights reserved.</p>
    </footer>

<script src="JS/auth.js"></script>
</body>
</html>

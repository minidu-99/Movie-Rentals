// Function to display the message box
function displayMessage(message, type) {
    const messageBox = document.getElementById('registerMessage');
    const messageText = document.getElementById('messageText');
    messageText.textContent = message;

    if (type === "error") {
        messageBox.style.backgroundColor = "#f44336"; // Red for errors
    } else if (type === "success") {
        messageBox.style.backgroundColor = "#4CAF50"; // Green for success
    }

    messageBox.classList.remove('message-hidden'); // Show the message box
}

// Function to close the message box
function closeMessage() {
    const messageBox = document.getElementById('registerMessage');
    messageBox.classList.add('message-hidden'); // Hide the message box
}

// Form submission event listener
document.getElementById('registerForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Validate empty fields
    if (!username || !password || !confirmPassword) {
        displayMessage("All fields are required!", "error");
        return;
    }

    // Validate password confirmation
    if (password !== confirmPassword) {
        displayMessage("Passwords do not match!", "error");
        return;
    }

    // Send registration data to the server (no rental data yet)
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "register_logic.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Redirect to login page with a success message after successful registration
            window.location.href = "./login.php?message=Registration successful!";
        }
    };

    const postData = `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`;
    xhr.send(postData);
});


    




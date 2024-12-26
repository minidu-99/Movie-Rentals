document.addEventListener("DOMContentLoaded", function () {
    const statsTab = document.getElementById("statsTab");
    const moviesTab = document.getElementById("moviesTab");
    const ordersTab = document.getElementById("ordersTab");
    const adminsTab = document.getElementById("adminsTab");

    const movieManagementSection = document.getElementById("movieManagementSection");
    const orderManagementSection = document.getElementById("orderManagementSection");
    const addAdminSection = document.getElementById("addAdminSection");
    const statsSection = document.getElementById("statsSection");
    const moviesTableBody = document.getElementById('moviesTableBody');

    // Hide all sections initially
    function hideAllSections() {
        movieManagementSection.style.display = "none";
        orderManagementSection.style.display = "none";
        addAdminSection.style.display = "none";
        statsSection.style.display = "none";
    }

    hideAllSections();
    statsSection.style.display = "block";
    loadMovies(); // Load movies when the statistics section is displayed

    // Show movies management section
    moviesTab.addEventListener("click", function (e) {
        e.preventDefault();
        hideAllSections();
        movieManagementSection.style.display = "block";
    });

    // Show orders management section
    ordersTab.addEventListener("click", function (e) {
        e.preventDefault();
        hideAllSections();
        orderManagementSection.style.display = "block";
        // Here you would typically make an AJAX call to fetch and display orders
    });

    // Show add admin section
    adminsTab.addEventListener("click", function (e) {
        e.preventDefault();
        hideAllSections();
        addAdminSection.style.display = "block";
    });

    // Show statistics section
    statsTab.addEventListener("click", function (e) {
        e.preventDefault();
        hideAllSections();
        statsSection.style.display = "block";
        loadMovies(); // Load movies when the statistics tab is opened
    });

    // Function to display movies in statistics section
    function loadMovies() {
        fetch('../Admin/load_movies.php')
            .then(response => response.json())
            .then(movies => {
                moviesTableBody.innerHTML = ''; // Clear the table body
                let totalMovies = 0;
                let totalCopies = 0;

                movies.forEach(movie => {
                    totalMovies++;
                    totalCopies += movie.copies;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${movie.title}</td>
                        <td>${movie.genre}</td>
                        <td>${movie.description}</td>
                        <td>${movie.copies}</td>
                        <td>${movie.year}</td>
                        <td>${movie.rating}</td>
                        <td><img src="${movie.image}" alt="${movie.title}" style="max-width: 100px;"></td>
                        <td><button onclick="confirmRemoveMovie(${movie.id})">Remove</button></td>
                    `;
                    moviesTableBody.appendChild(row);
                });

                document.getElementById('totalMoviesCount').innerText = totalMovies;
                document.getElementById('totalCopiesCount').innerText = totalCopies;
            })
            .catch(error => console.error('Error loading movies:', error));
    }

    // Function to remove a movie
    window.confirmRemoveMovie = function(movieId) {
        if (confirm("Are you sure you want to remove this movie?")) {
            removeMovie(movieId);
        }
    };

    function removeMovie(movieId) {
        fetch('remove_movie.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: movieId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                loadMovies(); // Refresh the movie list
            } else {
                alert('Failed to remove movie: ' + data.message);
            }
        })
        .catch(error => console.error('Error removing movie:', error));
    }

    // Add movie form submission
    const addMovieForm = document.getElementById("addMovieForm");
    addMovieForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(addMovieForm);
        const availability = document.getElementById("movieAvailability").checked;
        formData.append('movieAvailability', availability); // Add it to the FormData

        // Send AJAX request to the server
        fetch('add_movie.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("addMovieResponse").textContent = data.message;
            if (data.success) {
                addMovieForm.reset();
                document.getElementById("imagePreview").style.display = "none"; // Hide image preview
                loadMovies(); // Refresh the movie list
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("addMovieResponse").textContent = "An error occurred while adding the movie.";
        });
    });

    // Add admin form submission
    document.getElementById("addAdminForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const username = document.getElementById("adminUsername").value;
        const email = document.getElementById("adminEmail").value;
        const password = document.getElementById("adminPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        if (password !== confirmPassword) {
            document.getElementById("addAdminResponse").textContent = "Passwords do not match!";
            return;
        }

        const formData = new FormData();
        formData.append("adminUsername", username);
        formData.append("adminEmail", email);
        formData.append("adminPassword", password);

        // Send AJAX request to the server
        fetch('add_admin.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("addAdminResponse").textContent = data.message;
            if (data.success) {
                document.getElementById("addAdminForm").reset(); // Reset form after success
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById("addAdminResponse").textContent = "An error occurred while adding the admin.";
        });
    });

    
});

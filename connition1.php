<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sanitize user input
$verification_code = filter_input(INPUT_POST, 'verification_code', FILTER_SANITIZE_SPECIAL_CHARS);

if (empty($verification_code)) {
    die("Verification code cannot be empty.");
}

// Database connection details
$servername = "sql202.infinityfree.com";
$username = "if0_37694885";
$password = "0YN3dToFeBfy4";
$dbname = "if0_37694885_erosdatabase1";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the table and column exist
$table_check_query = "
    CREATE TABLE IF NOT EXISTS verification_codes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        code VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
";
if (!$conn->query($table_check_query)) {
    die("Table creation failed: " . $conn->error);
}

// Insert verification code
$stmt = $conn->prepare("INSERT INTO verification_codes (code) VALUES (?)");
if (!$stmt) {
    die("Statement preparation failed: " . $conn->error);
}

$stmt->bind_param("s", $verification_code);

if ($stmt->execute()) {
    echo "Verification code inserted successfully!";
    header("Location: code2.html");
    exit();
} else {
    die("Insert failed: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>

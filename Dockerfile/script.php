<?php
// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection
$servername = "sql12.freesqldatabase.com";  
$username = "sql12739212";  
$password = "j85ZMmHWMt";  
$dbname = "sql12739212";        

$conn = new mysqli($servername, $username, $password, $dbname, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get visitor's IP address
$ip_address = $_SERVER['REMOTE_ADDR'];

// Get today's date
$date = date('Y-m-d');

// Check if IP already exists for today
$sql = "SELECT * FROM visits WHERE ip_address = ? AND date = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("ss", $ip_address, $date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // IP exists for today; update visit count
    $sql = "UPDATE visits SET visit_count = visit_count + 1 WHERE ip_address = ? AND date = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $ip_address, $date);
} else {
    // IP does not exist for today; insert new record
    $sql = "INSERT INTO visits (ip_address, visit_count, date) VALUES (?, 1, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $ip_address, $date);
}

if (!$stmt->execute()) {
    die("Execution failed: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>

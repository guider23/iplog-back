<?php
// Enable CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    exit(0);
}
phpinfo();
try {
    // Database connection
    $dsn = "mysql:host=sql12.freesqldatabase.com;dbname=sql12739212;charset=utf8";
    $username = "sql12739212";
    $password = "j85ZMmHWMt";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get visitor's IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];
    // Get today's date
    $date = date('Y-m-d');

    // Check if IP already exists for today
    $stmt = $pdo->prepare("SELECT * FROM visits WHERE ip_address = ? AND date = ?");
    $stmt->execute([$ip_address, $date]);

    if ($stmt->rowCount() > 0) {
        // IP exists for today; update visit count
        $stmt = $pdo->prepare("UPDATE visits SET visit_count = visit_count + 1 WHERE ip_address = ? AND date = ?");
        $stmt->execute([$ip_address, $date]);
    } else {
        // IP does not exist for today; insert new record
        $stmt = $pdo->prepare("INSERT INTO visits (ip_address, visit_count, date) VALUES (?, 1, ?)");
        $stmt->execute([$ip_address, $date]);
    }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

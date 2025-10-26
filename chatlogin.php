<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Step 1: Script started<br>";

session_start();
echo "Step 2: Session started<br>";

// DB connection
$host = "localhost";
$user = "root";      
$password = "root";   // or "root" depending on your DB
$dbname = "medbot";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Step 3: Connection failed - " . $conn->connect_error);
} else {
    echo "Step 3: DB Connected<br>";
}

// Get form values
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
echo "Step 4: Got form values (email: $email)<br>";

// Prepare query
$sql = "SELECT * FROM users WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("❌ Step 5: SQL Prepare failed - " . $conn->error);
} else {
    echo "Step 5: SQL prepared<br>";
}

$stmt->bind_param("ss", $email, $password);
$stmt->execute();
echo "Step 6: Query executed<br>";

$result = $stmt->get_result();
if (!$result) {
    die("❌ Step 7: Query execution failed - " . $conn->error);
} else {
    echo "Step 7: Query result obtained<br>";
}

// Check login
if ($result->num_rows > 0) {
    echo "✅ Step 8: Login Success<br>";
    $_SESSION['email'] = $email;
    header("Location: chatbot_ui.php");
    exit();
} else {
    echo "❌ Step 8: Invalid email or password<br>";
}

$stmt->close();
$conn->close();
?>

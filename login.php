<?php
// Allow CORS (so Vite's React frontend can reach PHP backend)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

// Get raw POST data
$input = json_decode(file_get_contents("php://input"), true);

if (!isset($input['email']) || !isset($input['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing email or password"
    ]);
    exit;
}

$email = $input['email'];
$password = $input['password'];

// ✅ Connect to your MySQL database
$servername = "localhost";
$username = "root"; // change this if your DB user differs
$dbpassword = "";   // add password if you have one
$dbname = "your_database_name"; // change this to your actual DB name

$conn = new mysqli($servername, $username, $dbpassword, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}

// ✅ Check if user exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    exit;
}

$user = $result->fetch_assoc();

// ✅ Verify password
if (password_verify($password, $user['password'])) {
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user['id'],
            "email" => $user['email']
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid password"
    ]);
}

$stmt->close();
$conn->close();
?>

<?php
// CORS headers must come first
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(); // stop execution here for OPTIONS
}

// Now handle POST request
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

function sendError($msg, $code = 400) {
    http_response_code($code);
    echo json_encode(['message' => $msg]);
    exit;
}

if (!$email || !$password) sendError('Email and password are required');

// Database connection
$conn = new mysqli("127.0.0.1", "root", "", "dev_toolboard");
if ($conn->connect_error) sendError('Database connection failed', 500);

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(['message' => 'User registered successfully']);
} else {
    sendError('Registration failed: ' . $stmt->error, 500);
}

$stmt->close();
$conn->close();

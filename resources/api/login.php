<?php
session_start();

require_once 'db.php';

$db = new db();
$conn = $db->get_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION["user_id"] = $user['id'];
        $_SESSION["username"] = $user['username'];
        echo "Login successful! <a href='../index.php'>Go to dashboard</a>";
    } else {
        echo "Invalid username or password.";
    }
    $_SESSION["is_logged_in"] = true;
}
?>

<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" value="Login">
</form>

<?php
try {
    $pdo = new PDO($dsn, $user, $pass);
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

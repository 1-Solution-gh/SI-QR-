<?php
require_once 'connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    
    echo json_encode(['success' => $stmt->affected_rows > 0]);
    exit();
}

header('HTTP/1.1 400 Bad Request');
echo json_encode(['success' => false]);
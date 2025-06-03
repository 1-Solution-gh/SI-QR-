<?php
header('Content-Type: application/json');
require_once 'connection.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'get_threads':
            $stmt = $pdo->query("SELECT * FROM community_threads ORDER BY created_at DESC");
            $threads = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'threads' => $threads]);
            break;
            
        case 'create_thread':
            $userId = $_POST['user_id'];
            $content = $_POST['content'] ?? '';
            $mediaCaption = $_POST['media_caption'] ?? '';
            
            // Handle file upload
            $mediaUrl = '';
            $mediaType = '';
            
            if (isset($_FILES['media'])) {
                $uploadDir = 'uploads/community/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = uniqid() . '_' . basename($_FILES['media']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['media']['tmp_name'], $targetPath)) {
                    $mediaUrl = $targetPath;
                    $mediaType = strpos($_FILES['media']['type'], 'video') !== false ? 'video' : 'image';
                }
            }
            
            // Insert into database
            $stmt = $pdo->prepare("
                INSERT INTO community_threads 
                (user_id, content, media_url, media_type, media_caption, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$userId, $content, $mediaUrl, $mediaType, $mediaCaption]);
            
            echo json_encode(['success' => true]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
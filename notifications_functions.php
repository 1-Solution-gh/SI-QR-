<?php
function getUserNotifications($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT 
        id, title, message, icon, is_read, type, priority, created_at 
        FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getUnreadNotificationsCount($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count 
        FROM notifications 
        WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['count'];
}

function markNotificationAsRead($notification_id) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE notifications 
        SET is_read = 1 
        WHERE id = ?");
    $stmt->bind_param("i", $notification_id);
    return $stmt->execute();
}

function markAllNotificationsAsRead($user_id) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE notifications 
        SET is_read = 1 
        WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
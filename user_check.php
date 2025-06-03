<?php
// update_user.php
require_once 'connection.php'; // Your database connection file

// Initialize variables
$response = ['success' => false, 'message' => ''];
$updatedFields = [];

try {
    // Check if form was submitted
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Get user ID from form
    $user_id = $_POST['user_id'] ?? null;
    if (!$user_id) {
        throw new Exception('User ID not provided');
    }

    // Get current user data from database
    $stmt = $conn->prepare("SELECT email, phone, address, preference FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentData = $result->fetch_assoc();
    $stmt->close();

    if (!$currentData) {
        throw new Exception('User not found');
    }

    // Sanitize input data
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'] ?? '', FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'] ?? '', FILTER_SANITIZE_STRING);
    $preferences = filter_var($_POST['preference'] ?? '', FILTER_SANITIZE_STRING);

    // Prepare update statement for basic fields
    $updates = [];
    $params = [];
    $types = '';

    // Check each field for changes
    if ($email !== $currentData['email']) {
        $updates[] = 'email = ?';
        $params[] = $email;
        $types .= 's';
        $updatedFields[] = 'email';
    }

    if ($phone !== $currentData['phone']) {
        $updates[] = 'phone = ?';
        $params[] = $phone;
        $types .= 's';
        $updatedFields[] = 'phone';
    }

    if ($address !== $currentData['address']) {
        $updates[] = 'address = ?';
        $params[] = $address;
        $types .= 's';
        $updatedFields[] = 'address';
    }

    // Always update preferences 
    $updates[] = 'preference = ?';
    $params[] = $preferences;
    $types .= 's';
    $updatedFields[] = 'preference';

    // Only proceed if there are changes or preferences need to be set
    if (!empty($updates)) {
        // Add user ID to params
        $params[] = $user_id;
        $types .= 'i';

        // Build the update query
        $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception('Failed to update user data: ' . $stmt->error);
        }

        $stmt->close();
        $response['success'] = true;
        $response['message'] = 'User data updated successfully';
        $response['updated_fields'] = $updatedFields;
    } else {
        $response['success'] = true;
        $response['message'] = 'No changes detected';
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

// Close connection
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
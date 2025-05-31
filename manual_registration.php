<?php
session_start(); // Start session at the very beginning
require_once 'connection.php';

// Initialize variables
$errors = [];
$success = false;

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create database connection
    $conn = new mysqli($host, $user, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and validate input
    $full_name = trim($_POST['fullname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $nationality = trim($_POST['nationality'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $passport_number = trim($_POST['passportnumber'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['tel'] ?? '');

    // Validate required fields
    if (empty($full_name)) $errors[] = "Full name is required";
    if (empty($gender)) $errors[] = "Gender is required";
    if (empty($nationality)) $errors[] = "Nationality is required";
    if (empty($dob)) $errors[] = "Date of birth is required";
    if (empty($passport_number)) $errors[] = "Passport number is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone number is required";

    // Additional validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        // Prepare SQL statement
        $stmt = $conn->prepare("
            INSERT INTO users (
                full_name, 
                gender, 
                nationality, 
                dob, 
                passport_number, 
                email, 
                phone, 
                registration_method
            ) VALUES (?, ?, ?, ?, ?, ?, ?, 'manual')
        ");

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param(
                "sssssss",
                $full_name,
                $gender,
                $nationality,
                $dob,
                $passport_number,
                $email,
                $phone
            );

            // Execute the statement
            if ($stmt->execute()) {
                $success = true;
                $new_user_id = $stmt->insert_id; // Get the auto-incremented ID
                $_SESSION['new_user_id'] = $new_user_id; // Store in session
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }

    // Close connection
    $conn->close();
}

// Return JSON response if AJAX, otherwise redirect
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Registration successful!', 'user_id' => $new_user_id]);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
    exit();
} else {
    // For regular form submission
    if ($success) {
        header("Location: user-info.php");
        exit();
    } else {
        // Redirect back with errors
        $_SESSION['registration_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: register.php"); // Assuming your form is on register.php
        exit();
    }
}
?>
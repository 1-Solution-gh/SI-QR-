<?php

require_once '../rest/db.php'; // Include your database connection file

// Get form data
$company_name = $_POST['company_name'] ?? '';
$title = $_POST['title'];
$job_type = $_POST['job_type'];
$region = $_POST['region'];
$is_remote = isset($_POST['remote']) ? 1 : 0;
$description = $_POST['description'];
$responsibilities = $_POST['responsibilities'];
$requirements = $_POST['requirements'];
$email = $_POST['email'] ?? '';
$salary = $_POST['salary'] ?? '';

$company_image = '';
$upload_dir = 'uploads/';
$max_size = 1024 * 1024 * 3; // 3MB in bytes
$allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

if (isset($_FILES['company_image']) && $_FILES['company_image']['error'] === UPLOAD_ERR_OK) {
    $image_tmp = $_FILES['company_image']['tmp_name'];
    $image_name = basename($_FILES['company_image']['name']);
    $image_type = mime_content_type($image_tmp);
    $image_size = $_FILES['company_image']['size'];

    // Validate image type
    if (!in_array($image_type, $allowed_types)) {
        die("Error: Only JPG and PNG images are allowed.");
    }

    // Validate file size
    if ($image_size > $max_size) {
        die("Error: Image must be less than 3MB.");
    }

    // Save image
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir);
    }

    $unique_name = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", $image_name);
    $target_file = $upload_dir . $unique_name;

    if (move_uploaded_file($image_tmp, $target_file)) {
        $company_image = $target_file;
    } else {
        die("Error: Failed to upload image.");
    }
} else {
    // Optional: you can set a default image here if needed
    $company_image = '';
} 

// Insert into DB
$stmt = $conn->prepare("INSERT INTO jobs (title, job_type, region, is_remote, description, responsibilities, requirements, company_image,email, salary,company_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?)");
$stmt->bind_param("sssisssssss", $title, $job_type, $region, $is_remote, $description, $responsibilities, $requirements, $company_image, $email, $salary,$company_name);



if ($stmt->execute()) {
    // Store success flag in session
    session_start();
    $_SESSION['success'] = true;

    // Redirect to form or confirmation page
    header("Location: job-posted.php");
    exit;
}else {
    session_start();
    $_SESSION['success'] = true;
    // Redirect to form or confirmation page
    header("Location: job-posted.php");
}
?>

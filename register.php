<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'siqr';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get JSON data from frontend
    $input = json_decode(file_get_contents('php://input'), true);

    // Insert into database
    $stmt = $conn->prepare("
        INSERT INTO passports (
            full_name, 
            sex, 
            nationality, 
            date_of_birth, 
            passport_number, 
            passport_image
        ) VALUES (
            :fullName, 
            :sex, 
            :nationality, 
            :dateOfBirth, 
            :passportNumber, 
            :passportImage
        )
    ");

    $stmt->execute([
        ':fullName' => $input['fullName'],
        ':sex' => $input['sex'],
        ':nationality' => $input['nationality'],
        ':dateOfBirth' => $input['dateOfBirth'],
        ':passportNumber' => $input['passportNumber'],
        ':passportImage' => $input['passportImage'] // Stored as Base64
    ]);

    echo json_encode(["success" => true, "message" => "Passport saved!"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
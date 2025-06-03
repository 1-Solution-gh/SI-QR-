<?php
// Debug + CORS
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// DB Config
$host = 'localhost';
$db   = 'siqr';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// Connect to DB
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed: " . $e->getMessage()]);
    exit;
}

// Table + Action + ID + Body
$table = $_GET['table'] ?? null;
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;
$input = json_decode(file_get_contents("php://input"), true);

// Column Filters (safe public fields per table)
$columnFilters = [
    'users' => 'id, full_name, email, phone, nationality, gender, created_at',
    'housing_options' => 'id, title, type, price, price_period, beds, baths, is_available, image_url',
    'events' => 'id, title, description, event_date, event_time, location, category, is_recurring',
    'community_groups' => 'id, name, description, category, member_count, created_at',
    'emergency_services' => 'id, service_type, name, phone_number, 	address, is_24_hours',
    'restaurants' => 'id, restaurant_name, 	cuisine_type, price_range, 	address, rating, review_count, description, opening_hours, 	is_featured, image_url',
    'shopping' => 'id, store_name, 	category, description, 	address, rating, review_count, opening_hours, image_url',
    'schools' => 'id, name, type, description, grade_levels, features, address, website_url',
    'jobs' => 'id, title, company, job_type, salary, description, requirements, is_urgent, contact_email, posted_date, 	expiry_date',
    'transportation' => 'id, type, name, route, schedule, fare_info',
    'waste_management' => 'id, collection_type, schedule_date, 	schedule_time, area, is_recurring, recurrence_pattern,'
];


// Validate base request
if (!$table || !$action) {
    http_response_code(400);
    echo json_encode(["error" => "Missing table or action"]);
    exit;
}

// Main action handler
try {
    switch ($action) {
        case 'get':
            $columns = $columnFilters[$table] ?? '*';

            if ($id) {
                $stmt = $pdo->prepare("SELECT $columns FROM `$table` WHERE id = ?");
                $stmt->execute([$id]);
                $data = $stmt->fetch();
                echo json_encode($data ?: []);
            } else {
                $stmt = $pdo->query("SELECT $columns FROM `$table`");
                $data = $stmt->fetchAll();
                echo json_encode($data);
            }
            break;

        case 'add':
            if (!$input) throw new Exception("No input data received");
            $keys = array_keys($input);
            $placeholders = implode(',', array_fill(0, count($keys), '?'));
            $fields = '`' . implode('`,`', $keys) . '`';
            $sql = "INSERT INTO `$table` ($fields) VALUES ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($input));
            echo json_encode(["success" => true, "insert_id" => $pdo->lastInsertId()]);
            break;

        case 'update':
            if (!$input || !isset($input['id'])) throw new Exception("Missing ID for update");
            $id = $input['id'];
            unset($input['id']);
            $setFields = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($input)));
            $stmt = $pdo->prepare("UPDATE `$table` SET $setFields WHERE id = ?");
            $stmt->execute([...array_values($input), $id]);
            echo json_encode(["success" => true]);
            break;

        case 'delete':
            if (!$id) throw new Exception("Missing ID for delete");
            $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = ?");
            $stmt->execute([$id]);
            echo json_encode(["success" => true]);
            break;

        default:
            throw new Exception("Invalid action: $action");
    }
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(["error" => $ex->getMessage()]);
}

<?php
header('Content-Type: application/json');
require 'connection.php';

// List of allowed tables for security
$allowedTables = [
    'users', 'housing_options', 'events', 'community_groups', 'notifications',
    'emergency_services', 'jobs', 'shopping', 'restaurants', 'schools',
    'transportation', 'waste_management', 'recycling_centers'
];
$table = $_GET['table'] ?? '';
$action = $_GET['action'] ?? '';

// Validate table name
if (!in_array($table, $allowedTables)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid table specified']);
    exit;
}

// Validate action
$allowedActions = ['get', 'add', 'update', 'delete'];
if (!in_array($action, $allowedActions)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action specified']);
    exit;
}

// Get all records or single record
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
    try {
        $query = "SELECT * FROM `$table`"; // Backticks for table name
        
        // If specific ID is requested
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $query .= " WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $pdo->query($query);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        if ($data === false) {
            $data = []; // Return empty array if no results
        }
        
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// Add new record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'add') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input');
        }
        
        // Validate input is not empty
        if (empty($input)) {
            throw new Exception('No data provided');
        }
        
        $columns = [];
        $placeholders = [];
        $values = [];
        
        foreach ($input as $key => $value) {
            // Basic validation - skip null values or empty strings unless required
            if ($value !== null && $value !== '') {
                $columns[] = "`$key`"; // Backticks for column names
                $placeholders[] = ":$key";
                $values[":$key"] = $value;
            }
        }
        
        if (empty($columns)) {
            throw new Exception('No valid data provided');
        }
        
        $columnsStr = implode(', ', $columns);
        $placeholdersStr = implode(', ', $placeholders);
        
        $stmt = $pdo->prepare("INSERT INTO `$table` ($columnsStr) VALUES ($placeholdersStr)");
        $stmt->execute($values);
        
        echo json_encode([
            'success' => true, 
            'id' => $pdo->lastInsertId(),
            'message' => 'Record added successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Update record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON input');
        }
        
        if (!isset($input['id'])) {
            throw new Exception('ID is required for update');
        }
        
        $id = (int)$input['id'];
        unset($input['id']);
        
        // Validate we have data to update
        if (empty($input)) {
            throw new Exception('No data provided for update');
        }
        
        $set = [];
        $values = [':id' => $id];
        
        foreach ($input as $key => $value) {
            // Skip null values (if you want to allow nulls, remove this check)
            if ($value !== null) {
                $set[] = "`$key` = :$key";
                $values[":$key"] = $value;
            }
        }
        
        if (empty($set)) {
            throw new Exception('No valid data provided for update');
        }
        
        $setStr = implode(', ', $set);
        
        $stmt = $pdo->prepare("UPDATE `$table` SET $setStr WHERE id = :id");
        $stmt->execute($values);
        
        echo json_encode([
            'success' => true,
            'affected_rows' => $stmt->rowCount(),
            'message' => 'Record updated successfully'
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Delete record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    try {
        if (!isset($_GET['id'])) {
            throw new Exception('ID is required for deletion');
        }
        
        $id = (int)$_GET['id'];
        
        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        echo json_encode([
            'success' => $stmt->rowCount() > 0,
            'affected_rows' => $stmt->rowCount(),
            'message' => $stmt->rowCount() > 0 ? 'Record deleted successfully' : 'No record found to delete'
        ]);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
?>
<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=siqr", "root", "");
    echo "Connected!";
} catch (PDOException $e) {
    echo "Failed: " . $e->getMessage();
}
?>

<?php
session_start(); // Start session
require_once 'connection.php';

// Check for user ID in session
if (!isset($_SESSION['new_user_id'])) {
    die("Please complete registration first");
}

$user_id = intval($_SESSION['new_user_id']);

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize output
function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Get user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("User not found");
}

$user = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="user-info.css">
  <title>SI QR Info Hun - User Information</title>
</head>
<body>
<div class="header">
  <span class="app-name">SI QR Info Hub</span>
</div>
  <div class="container">
    <div class="form-container">
      <h1>User Information</h1>
      <form id="userForm" method="post" action="user_check.php">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" value="<?php echo sanitize($user['full_name'] ?? ''); ?>" disabled/>
        </div>
        
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?php echo sanitize($user['email'] ?? ''); ?>" required />
        </div>
        
        <div class="form-group">
          <label for="dob">Date of Birth</label>
          <input type="date" id="dob" value="<?php echo sanitize($user['dob'] ?? ''); ?>" disabled>
        </div>
        
        <div class="form-group">
          <label for="nationality">Nationality</label>
          <input type="text" name="nationality" id="nationality" value="<?php echo sanitize($user['nationality'] ?? ''); ?>" disabled />
        </div>
        
        <div class="form-group">
          <label for="sex">Gender</label>
          <input type="text" id="sex" name="sex" value="<?php echo sanitize($user['gender'] ?? ''); ?>" disabled />
        </div>
        
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" value="<?php echo sanitize($user['phone'] ?? ''); ?>" required />
        </div>
        
        <div class="form-group">
          <label for="address">Address</label>
          <input type="text" id="address" name="address" value="<?php echo sanitize($user['address'] ?? ''); ?>" required />
        </div>
        
        <div class="form-group">
          <label for="preferences">Preferences</label>
          <input type="text" id="preference" name="preference"  value="<?php echo sanitize($user['preference'] ?? ''); ?>"  placeholder="e.g., Housing preferences, interests" />
        </div>
        
        <button type="submit" class="scan-btn">Confirm</button>
      </form>
    </div>
  </div>

  <script src="user.js"></script>
</body>
</html>
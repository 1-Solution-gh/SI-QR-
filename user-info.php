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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <title>SI QR Info Hun - User Information</title>
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 30px;
      border-radius: 10px;
      width: 80%;
      max-width: 500px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .modal-icon {
      font-size: 50px;
      color: #4CAF50;
      margin-bottom: 20px;
    }
    
    .modal-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      margin-top: 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    
    .modal-btn:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

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

  <!-- Success Modal -->
  <div id="successModal" class="modal">
    <div class="modal-content">
      <div class="modal-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <h2>Congratulations!</h2>
      <p>A PIN code has been sent to your email. Use it to login now.</p>
      <button class="modal-btn" onclick="window.location.href='login.php'">Proceed to Login</button>
    </div>
  </div>

  <script src="user.js"></script>
  <script>
    // Check if we should show the modal
    <?php if (isset($_SESSION['show_success_modal']) && $_SESSION['show_success_modal']): ?>
      document.getElementById('successModal').style.display = 'block';
      <?php unset($_SESSION['show_success_modal']); ?>
    <?php endif; ?>
  </script>
</body>
</html>
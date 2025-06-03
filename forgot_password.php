<?php
session_start();
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Create connection
    $conn = new mysqli($host, $user, $password, $database);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id, pin_code FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Send email with PIN code (similar to the registration email)
        require 'vendor/autoload.php';
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.example.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@example.com';
            $mail->Password   = 'your_password';
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            
            // Recipients
            $mail->setFrom('no-reply@siqrinfohub.com', 'SI QR Info Hub');
            $mail->addAddress($email);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your SI QR Info Hub PIN Code';
            
            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                    .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; border-radius: 5px 5px 0 0; }
                    .content { padding: 20px; }
                    .pin-code { font-size: 24px; font-weight: bold; text-align: center; margin: 20px 0; padding: 10px; background-color: #f4f4f4; border-radius: 5px; }
                    .footer { margin-top: 20px; text-align: center; font-size: 12px; color: #777; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Password Recovery</h2>
                    </div>
                    <div class="content">
                        <p>Hello,</p>
                        <p>You requested your login PIN code for SI QR Info Hub. Here it is:</p>
                        
                        <div class="pin-code">' . $user['pin_code'] . '</div>
                        
                        <p>Use this PIN code to login to your account. If you didn\'t request this, please contact support immediately.</p>
                    </div>
                    <div class="footer">
                        <p>&copy; ' . date('Y') . ' SI QR Info Hub. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
            ';
            
            $mail->AltBody = "Password Recovery\n\nYour login PIN code is: " . $user['pin_code'] . "\n\nUse this PIN code to login to your account.";
            
            $mail->send();
            $success = "We've sent your PIN code to your email address.";
        } catch (Exception $e) {
            $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "No account found with that email address.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - SI QR Info Hub</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    
    .forgot-container {
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    
    .logo {
      color: #4CAF50;
      font-size: 24px;
      margin-bottom: 20px;
    }
    
    h2 {
      margin-bottom: 20px;
      color: #333;
    }
    
    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }
    
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    
    input[type="email"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }
    
    .btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      width: 100%;
    }
    
    .btn:hover {
      background-color: #45a049;
    }
    
    .message {
      margin-top: 20px;
      padding: 10px;
      border-radius: 4px;
    }
    
    .success {
      background-color: #dff0d8;
      color: #3c763d;
    }
    
    .error {
      background-color: #f2dede;
      color: #a94442;
    }
    
    .back-to-login {
      margin-top: 20px;
      display: block;
      color: #4CAF50;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="forgot-container">
    <div class="logo">SI QR Info Hub</div>
    <h2>Forgot Password</h2>
    <p>Enter your email address to receive your login PIN code.</p>
    
    <?php if (isset($success)): ?>
      <div class="message success">
        <?php echo $success; ?>
      </div>
      <a href="login.php" class="back-to-login">Back to Login</a>
    <?php else: ?>
      <?php if (isset($error)): ?>
        <div class="message error">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      
      <form method="POST">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required>
        </div>
        
        <button type="submit" class="btn">Send PIN Code</button>
      </form>
      
      <a href="login.php" class="back-to-login">Back to Login</a>
    <?php endif; ?>
  </div>
</body>
</html>
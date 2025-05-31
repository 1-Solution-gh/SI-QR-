<?php
session_start();
if (!isset($_SESSION['success'])) {
    header("Location: index.php");
    exit;
}
// Clear the success flag after showing the message
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Job Posted Successfully</title>
  <link rel="stylesheet" href="../fontawesome/css/all.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f9;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .success-box {
      background-color: white;
      padding: 40px 60px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 12px;
      text-align: center;
    }

    .success-box h1 {
      color: #00b894;
      font-size: 28px;
      margin-bottom: 10px;
    }

    .success-box p {
      color: #333;
      margin-bottom: 25px;
    }

    .success-box a {
      display: inline-block;
      padding: 10px 20px;
      background-color: #0984e3;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }

    .success-box a:hover {
      background-color: #74b9ff;
    }
  </style>
</head>
<body>

<div class="success-box">
  <h1><i class="fa-solid fa-briefcase"></i> Job Posted Successfully!</h1>
  <p>Your job listing has been saved and is now live.</p>
  <a href="index.php">← Back to Dashboard</a>
</div>

</body>
</html>

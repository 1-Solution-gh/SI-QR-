<?php
require_once 'connection.php';
session_start();

// Handle form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = $_POST['pin'] ?? '';
    
    if (empty($pin)) {
        $error = "Please enter your PIN";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE pin_code = ?");
        $stmt->bind_param("s", $pin);
        $stmt->execute();
        $result = $stmt->get_result();
        
       // In login.php, after successful login
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['logged_in'] = true;
    $_SESSION['fingerprint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    header("Location: home.php");
    exit();
}
        
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI QR - LOGIN</title>
    <style>

        /* Premium Color Palette */
:root {
    --primary: #4361ee;  /* Vibrant but trustworthy blue */
    --primary-light: #4895ef; /* Lighter accent */
    --secondary: #3f37c9; /* Deep blue for contrast */
    --accent: #4cc9f0;   /* Fresh teal for CTAs */
    --dark: #14213d;     /* Almost black for text */
    --light: #f8f9fa;    /* Clean background */
    --gray: #adb5bd;     /* Neutral gray */
    --success: #52b788;  /* Calming green */
    --border-radius: 12px;
    --shadow: 0 4px 20px rgba(0,0,0,0.08);
    --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .logo {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #2c3e50;
            font-weight: bold;
        }
        .pin-input {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        .pin-input input {
            width: 80px;
            height: 50px;
            text-align: center;
            font-size: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        .pin-input input:focus {
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }
        .login-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        .login-btn:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        .forgot-pin {
            margin-top: 1rem;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        .forgot-pin a {
            color: #3498db;
            text-decoration: none;
        }

        /* Buttons - Premium Style */
.btn {
    padding: 1rem 2rem;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(to right, var(--primary), var(--primary-light));
    color: white;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-primary::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-primary:hover::after {
    opacity: 1;
}


.btn-secondary {
    background: linear-gradient(to right, var(--gray), var(--light));
    color: var(--primary);
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-secondary::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-secondary:hover::after {
    opacity: 1;
}
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">SIQR</div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="pin-input">
                <input type="password" name="pin" maxlength="6" pattern="\d{6}" 
                       title="Please enter a 6-digit PIN" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary animate-slide delay-3">Login</button>
        </form>
        
        <div class="forgot-pin">
            <a href="forgot-pin.php">Forgot your PIN?</a>
        </div>
    </div>

    <script>
        // Auto-focus the PIN input and allow only numbers
        document.querySelector('input[name="pin"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
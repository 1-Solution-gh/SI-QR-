<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SI QR Info Hun - Welcome!</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    :root {
      --primary: #004b8d;
      --accent: #00c897;
      --bg-light: #f0f4f8;
      --bg-dark: #ffffff;
      --text-dark: #111827;
      --text-light: #6b7280;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-light);
      color: var(--text-dark);
    }

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 1rem;
    }

    .confirmation-screen {
      background: var(--bg-dark);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      width: 100%;
    }

    .success-message h1,
    .welcome-message h2 {
      color: var(--primary);
      margin-bottom: 0.5rem;
    }

    .success-message p,
    .welcome-message p {
      color: var(--text-light);
      margin-bottom: 1.5rem;
    }

    .feature-list {
      list-style: none;
      padding: 0;
      margin-bottom: 2rem;
    }

    .feature-list li {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
      font-size: 1rem;
      color: var(--text-dark);
    }

    .feature-list i {
      color: var(--accent);
      margin-right: 0.75rem;
      font-size: 1.25rem;
    }

    .scan-btn {
      background-color: var(--primary);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 0.5rem;
      font-size: 1rem;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    .scan-btn:hover {
      background-color: var(--accent);
    }

    @media (max-width: 600px) {
      .confirmation-screen {
        padding: 1.5rem;
        text-align: center;
      }

      .feature-list li {
        justify-content: center;
        font-size: 0.95rem;
      }

      .scan-btn {
        font-size: 0.95rem;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="confirmation-screen">
      <div class="success-message">
        <h1>Welcome to SI QR Info!</h1>
        <p>This platform helps you streamline information access.</p>
      </div>

      <div class="welcome-message">
        <h2>Quick. Efficient. Smart.</h2>
        <p>Manage, share, and explore securely with your QR code.</p>
      </div>

      <ul class="feature-list">
        <li><i class="fas fa-home"></i>Home Integration</li>
        <li><i class="fas fa-calendar-alt"></i>Event Scheduling</li>
        <li><i class="fas fa-users"></i>User Management</li>
        <li><i class="fas fa-concierge-bell"></i>Customer Services</li>
        <li><i class="fas fa-newspaper"></i>News & Updates</li>
      </ul>

      <button class="scan-btn" onclick="location.href='login.php'">Get Started</button>
    </div>
  </div>
</body>

</html>

<?php
// Start session and include database connection
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user data from database using MySQLi
$stmt = $conn->prepare("SELECT full_name, email, phone, address, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']); // "i" for integer
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Extract join year from created_at
if ($user_data) {
    $user_data['join_year'] = date('Y', strtotime($user_data['created_at']));
}

// Fetch user stats using MySQLi
$stmt = $conn->prepare("SELECT 
    (SELECT COUNT(*) FROM event_attendees WHERE user_id = ?) as events_attended,
    (SELECT COUNT(*) FROM community_members WHERE user_id = ?) as communities,
    (SELECT COUNT(*) FROM transport_taken WHERE user_id = ?) as transport");
$stmt->bind_param("iii", $_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();

// Close statement
$stmt->close();

// Fetch notifications
require_once 'notifications_functions.php';
$notifications = getUserNotifications($_SESSION['user_id']);
$unread_count = getUnreadNotificationsCount($_SESSION['user_id']);

// Categorize notifications by time (recent/earlier)
$recent_notifications = [];
$earlier_notifications = [];
$one_day_ago = date('Y-m-d H:i:s', strtotime('-1 day'));

foreach ($notifications as $notification) {
    if ($notification['created_at'] > $one_day_ago) {
        $recent_notifications[] = $notification;
    } else {
        $earlier_notifications[] = $notification;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SI QR Info Hun</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="spa.css">
  <style>
     :root {
            /* Keep your original color variables */
            --primary: #2f80ed;
            --accent: #f2994a;
            --bg: #f9f9f9;
            --text: #333;
            
            /* Add new variables for sidebar behavior */
            --sidebar-width: 80px;
            --sidebar-expanded: 220px;
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* Collapsible Sidebar */
        .sidebar-nav {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #fff;
            color: var(--text);
            transition: var(--transition);
            z-index: 100;
            overflow: hidden;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            padding: 1.5rem 0;
            display: flex;
            flex-direction: column;
            border-radius: 0 16px 16px 0;
        }

        .sidebar-nav:hover {
            width: var(--sidebar-expanded);
        }

        .sidebar-collapsed {
            width: var(--sidebar-width);
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            padding: 0.8rem 1.5rem;
            margin: 0.5rem 0;
            color: var(--text);
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }

        .sidebar-nav-item:hover,
        .sidebar-nav-item.active {
            background-color: rgba(47, 128, 237, 0.1);
            color: var(--primary);
        }

        .sidebar-nav-item .icon {
            font-size: 1.2rem;
            min-width: 40px;
            text-align: center;
            color: inherit;
        }

        .sidebar-nav-item span:not(.icon) {
            opacity: 0;
            transition: opacity 0.2s ease;
            margin-left: 10px;
        }

        .sidebar-nav:hover .sidebar-nav-item span:not(.icon) {
            opacity: 1;
        }

        /* Logo container in sidebar */
        .logo-container {
            padding: 1rem;
            display: none; /* Hidden by default */
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .logo-container .logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
        }

        .logo-container h2 {
            font-size: 1.2rem;
            white-space: nowrap;
            overflow: hidden;
        }

         /* Responsive Styles */
         @media (max-width: 768px) {
            .sidebar-nav {
                display: none; /* Hide sidebar on mobile */
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-bottom: 70px; /* Space for footer nav */
            }

            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .search-bar {
                width: 100%;
            }

            .footer-nav {
                display: block; /* Show footer nav on mobile */
            }
        }

        @media (min-width: 769px) {
            .logo-container {
                display: flex; /* Show logo container on desktop */
            }
        }

  </style>
</head>

<body>
  <!-- Collapsible Sidebar -->
  <nav class="sidebar-nav" id="sidebar">
        <div class="logo-container">
            <div class="logo"></div>
            <h2>SI QR</h2>
        </div>
    
        <a href="home.php" class="sidebar-nav-item active" id="nav-home">
            <span class="icon"><i class="fas fa-home"></i></span>
            <span>Home</span>
        </a>
        <a href="spa.php?page=notifications" class="sidebar-nav-item" id="nav-community">
            <span class="icon"><i class="fa-solid fa-bell"></i></span>
            <span>Notification</span>
        </a>
        <a href="spa.php?page=profile" class="sidebar-nav-item" id="nav-profile">
            <span class="icon"><i class="fas fa-user"></i></span>
            <span>Profile</span>
        </a>
        <a href="spa.php?page=settings" class="sidebar-nav-item" id="nav-settings">
            <span class="icon"><i class="fa-solid fa-sliders"></i></span>
            <span>Settings</span>
        </a>
        <a href="spa.php?page=regulations" class="sidebar-nav-item" id="nav-events">
            <span class="icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
            <span>Regulations</span>
        </a>
    </nav>

    <!-- Main Content -->
    <div class="content-container">
      <div class="container">
        <!-- Profile Page -->
        <div class="profile-page" id="profilePage">
    <div class="page-header">
        <h1 class="page-title">My Profile</h1>
        <button class="back-button" id="backButton">
            <i class="fas fa-arrow-left"></i> Back
        </button>
    </div>

    <div class="profile-header">
        <img src="OIP.jpeg" alt="Profile" class="profile-avatar">
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($user_data['full_name']); ?></h2>
            <p>Member since <?php echo htmlspecialchars($user_data['join_year']); ?></p>
            <p><?php echo !empty($user_data['address']) ? htmlspecialchars($user_data['address']) : 'Address not specified'; ?></p>
        </div>
    </div>

    <div class="profile-stats">
        <div class="stat-item">
            <div class="stat-value"><?php echo htmlspecialchars($stats['events_attended']); ?></div>
            <div class="stat-label">Events Attended</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo htmlspecialchars($stats['communities']); ?></div>
            <div class="stat-label">Communities</div>
        </div>
        <div class="stat-item">
            <div class="stat-value"><?php echo htmlspecialchars($stats['transport']); ?></div>
            <div class="stat-label">Transports</div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Personal Information</h3>
            <button class="btn btn-outline">Edit</button>
        </div>
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user_data['full_name']); ?>" readonly>
        </div>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
        </div>
        <div class="form-group">
            <label class="form-label">Phone Number</label>
            <input type="tel" class="form-control" value="<?php echo htmlspecialchars($user_data['phone']); ?>" readonly>
        </div>
        <div class="form-group">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" value="<?php echo !empty($user_data['address']) ? htmlspecialchars($user_data['address']) : 'Not specified'; ?>" readonly>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Account Security</h3>
        </div>
        <button class="btn btn-outline" style="width: 100%; margin-bottom: 1rem;">
            Change Password
        </button>
        <button class="btn btn-outline" style="width: 100%;">
            Two-Factor Authentication
        </button>
    </div>
</div>

        <!-- Settings Page (Hidden by default) -->
        <div class="settings-page" id="settingsPage" style="display: none;">
          <div class="page-header">
            <h1 class="page-title">Settings</h1>
            <button class="back-button" id="backButtonSettings">
              <i class="fas fa-arrow-left"></i> Back
            </button>
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">App Preferences</h3>
            </div>
            <div class="settings-group">
              <div class="settings-item">
                <div class="settings-label">
                  <span class="icon"><i class="fas fa-moon"></i></span>
                  <span>Dark Mode</span>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox">
                  <span class="toggle-slider"></span>
                </label>
              </div>
              <div class="settings-item">
                <div class="settings-label">
                  <span class="icon"><i class="fas fa-bell"></i></span>
                  <span>Notifications</span>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" checked>
                  <span class="toggle-slider"></span>
                </label>
              </div>
              <div class="settings-item">
                <div class="settings-label">
                  <span class="icon"><i class="fas fa-language"></i></span>
                  <span>Language</span>
                </div>
                <select class="form-control" style="width: auto;">
                  <option>English</option>
                  <option>Spanish</option>
                  <option>French</option>
                </select>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Privacy Settings</h3>
            </div>
            <div class="settings-group">
              <div class="settings-item">
                <div class="settings-label">
                  <span class="icon"><i class="fas fa-user-shield"></i></span>
                  <span>Show Profile to Others</span>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox" checked>
                  <span class="toggle-slider"></span>
                </label>
              </div>
              <div class="settings-item">
                <div class="settings-label">
                  <span class="icon"><i class="fas fa-map-marker-alt"></i></span>
                  <span>Share Location</span>
                </div>
                <label class="toggle-switch">
                  <input type="checkbox">
                  <span class="toggle-slider"></span>
                </label>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Help & Support</h3>
            </div>
            <button class="btn btn-outline" style="width: 100%; margin-bottom: 1rem;">
              <i class="fas fa-question-circle"></i> Help Center
            </button>
            <button class="btn btn-outline" style="width: 100%; margin-bottom: 1rem;">
              <i class="fas fa-envelope"></i> Contact Support
            </button>
            <button class="btn btn-outline" style="width: 100%;">
              <i class="fas fa-info-circle"></i> About App
            </button>
          </div>
        </div>

        <!-- Notifications Page (Hidden by default) -->
        <div class="notifications-page" id="notificationsPage" style="display: none;">
    <div class="page-header">
        <h1 class="page-title">Notifications 
            <?php if ($unread_count > 0): ?>
            <span class="notification-badge"><?= $unread_count ?></span>
            <?php endif; ?>
        </h1>
        <button class="back-button" id="backButtonNotifications">
            <i class="fas fa-arrow-left"></i> Back
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent</h3>
            <button class="btn btn-outline" id="markAllReadBtn">Mark all as read</button>
        </div>
        <?php if (empty($recent_notifications)): ?>
        <div class="notification-empty">
            <i class="fas fa-bell-slash"></i>
            <p>No recent notifications</p>
        </div>
        <?php else: ?>
            <?php foreach ($recent_notifications as $notification): ?>
            <div class="notification-item <?= $notification['is_read'] ? '' : 'notification-unread' ?>" 
                 data-id="<?= $notification['id'] ?>">
                <div class="notification-icon">
                    <i class="fas <?= htmlspecialchars($notification['icon']) ?>"></i>
                </div>
                <div class="notification-content">
                    <h4 class="notification-title"><?= htmlspecialchars($notification['title']) ?></h4>
                    <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                    <div class="notification-time">
                        <?= time_elapsed_string($notification['created_at']) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Earlier</h3>
        </div>
        <?php if (empty($earlier_notifications)): ?>
        <div class="notification-empty">
            <i class="fas fa-bell-slash"></i>
            <p>No older notifications</p>
        </div>
        <?php else: ?>
            <?php foreach ($earlier_notifications as $notification): ?>
            <div class="notification-item <?= $notification['is_read'] ? '' : 'notification-unread' ?>" 
                 data-id="<?= $notification['id'] ?>">
                <div class="notification-icon">
                    <i class="fas <?= htmlspecialchars($notification['icon']) ?>"></i>
                </div>
                <div class="notification-content">
                    <h4 class="notification-title"><?= htmlspecialchars($notification['title']) ?></h4>
                    <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                    <div class="notification-time">
                        <?= time_elapsed_string($notification['created_at']) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

        <!-- Regulations Page (Hidden by default) -->
        <div class="regulations-page" id="regulationsPage" style="display: none;">
          <div class="page-header">
            <h1 class="page-title">Community Regulations</h1>
            <button class="back-button" id="backButtonRegulations">
              <i class="fas fa-arrow-left"></i> Back
            </button>
          </div>

          <div class="card">
            <div class="regulation-category">
              <h3 class="card-title">Housing Policies</h3>
              <div class="regulation-item">
                <div class="regulation-question">
                  <span>What are the quiet hours?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="regulation-answer">
                  <p>Quiet hours are from 10:00 PM to 7:00 AM on weekdays and 11:00 PM to 8:00 AM on weekends. During these times, residents should keep noise to a minimum.</p>
                </div>
              </div>
              <div class="regulation-item">
                <div class="regulation-question">
                  <span>Can I sublet my unit?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="regulation-answer">
                  <p>Subletting is not permitted without prior written approval from the housing administration. Violations may result in termination of your lease agreement.</p>
                </div>
              </div>
            </div>

            <div class="regulation-category">
              <h3 class="card-title">Common Areas</h3>
              <div class="regulation-item">
                <div class="regulation-question">
                  <span>What are the gym hours?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="regulation-answer">
                  <p>The gym is open from 5:00 AM to 11:00 PM daily. Residents must clean equipment after use and follow all posted safety guidelines.</p>
                </div>
              </div>
              <div class="regulation-item">
                <div class="regulation-question">
                  <span>Can I reserve the party room?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="regulation-answer">
                  <p>Yes, the party room can be reserved up to 30 days in advance through the housing portal. A refundable deposit is required.</p>
                </div>
              </div>
            </div>

            <div class="regulation-category">
              <h3 class="card-title">Safety & Security</h3>
              <div class="regulation-item">
                <div class="regulation-question">
                  <span>What should I do in case of emergency?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="regulation-answer">
                  <p>For life-threatening emergencies, call 911 immediately. For building emergencies, contact security at extension 555 from any building phone.</p>
                </div>
              </div>
              <div class="regulation-item">
                <div class="regulation-question">
                  <span>Are guests allowed?</span>
                  <i class="fas fa-chevron-down"></i>
                </div>
                <div class="regulation-answer">
                  <p>Residents may have guests for up to 14 consecutive days. All guests must be registered with security and accompanied by the resident at all times.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Footer Navigation -->
  <div class="footer-nav">
    <div class="footer-nav-items">
      <a href="#" class="footer-nav-item" id="nav-home-mobile">
        <span class="icon"><i class="fas fa-home"></i></span>
        <span>Home</span>
      </a>
      <a href="#" class="footer-nav-item" id="nav-community-mobile">
        <span class="icon"><i class="fa-solid fa-bell"></i></span>
        <span>Notification</span>
      </a>
      <a href="#" class="footer-nav-item active" id="nav-profile-mobile">
        <span class="icon"><i class="fas fa-user"></i></span>
        <span>Profile</span>
      </a>
      <a href="#" class="footer-nav-item" id="nav-settings-mobile">
        <span class="icon"><i class="fa-solid fa-sliders"></i></span>
        <span>Settings</span>
      </a>
      <a href="#" class="footer-nav-item" id="nav-events-mobile">
        <span class="icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
        <span>Regulations</span>
      </a>
    </div>
  </div>

  
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get URL parameter to determine which page to show
    const urlParams = new URLSearchParams(window.location.search);
    const pageParam = urlParams.get('page') || 'profile'; // Default to profile
    
    // Get all page elements
    const pages = {
      profile: document.getElementById('profilePage'),
      settings: document.getElementById('settingsPage'),
      notifications: document.getElementById('notificationsPage'),
      regulations: document.getElementById('regulationsPage')
    };
    
    // Get all navigation items
    const navItems = {
      notifications: [document.getElementById('nav-community'), document.getElementById('nav-community-mobile')],
      profile: [document.getElementById('nav-profile'), document.getElementById('nav-profile-mobile')],
      settings: [document.getElementById('nav-settings'), document.getElementById('nav-settings-mobile')],
      regulations: [document.getElementById('nav-events'), document.getElementById('nav-events-mobile')]
    };
    
    // Get all back buttons
    const backButtons = {
      profile: document.getElementById('backButton'),
      settings: document.getElementById('backButtonSettings'),
      notifications: document.getElementById('backButtonNotifications'),
      regulations: document.getElementById('backButtonRegulations')
    };
    
    // Function to show a specific page and hide others
    function showPage(pageToShow) {
      // Hide all pages
      Object.values(pages).forEach(page => {
        page.style.display = 'none';
      });
      
      // Show the selected page
      if (pages[pageToShow]) {
        pages[pageToShow].style.display = 'block';
      }
      
      // Update active state for all nav items
      Object.entries(navItems).forEach(([page, items]) => {
        items.forEach(item => {
          if (page === pageToShow) {
            item.classList.add('active');
          } else {
            item.classList.remove('active');
          }
        });
      });
      
      // Update URL without reload
      history.pushState(null, null, `spa.php?page=${pageToShow}`);
    }
    
    // Set up navigation item click handlers
    Object.entries(navItems).forEach(([page, items]) => {
      items.forEach(item => {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          showPage(page);
        });
      });
    });
    
    // Set up back button click handlers
    Object.entries(backButtons).forEach(([page, button]) => {
      if (button) {
        button.addEventListener('click', function(e) {
          e.preventDefault();
          showPage('profile'); // Default back to profile
        });
      }
    });
    
    // Set up regulation FAQ toggles
    const regulationQuestions = document.querySelectorAll('.regulation-question');
    regulationQuestions.forEach(question => {
      question.addEventListener('click', function() {
        const answer = this.nextElementSibling;
        const icon = this.querySelector('i');
        
        answer.classList.toggle('show');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
      });
    });
    
    // Initialize with page from URL parameter
    showPage(pageParam);
  });
</script>
</body>

</html>
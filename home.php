<?php
session_start();
require_once 'connection.php';
// In home.php, replace the session checks with:
if (!isset($_SESSION['user_id'])) {  // Added missing closing parenthesis
    header('Location: login.php');
    exit();
}

// Optional: Keep fingerprint check but make it less strict
if (!isset($_SESSION['fingerprint'])) {
    $_SESSION['fingerprint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
}

// Fetch user data from database using MySQLi
$stmt = $conn->prepare("SELECT full_name, email, phone, address, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']); // "i" for integer
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI QR Info Hub - Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="home.css">
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

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            transition: var(--transition);
            min-height: 100vh;
            padding-bottom: 70px; /* Space for footer nav */
        }

        .sidebar-nav:hover ~ .main-content {
            margin-left: var(--sidebar-expanded);
        }

        /* Dashboard Content */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            width: 300px;
        }

        .search-bar input {
            border: none;
            outline: none;
            padding: 0.5rem;
            width: 100%;
            font-size: 0.9rem;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        /* Mobile Footer Navigation */
        .footer-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .footer-nav-items {
            display: flex;
            justify-content: space-around;
        }

        .footer-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text);
            padding: 0.5rem;
            font-size: 0.8rem;
            flex: 1;
        }

        .footer-nav-item .icon {
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }

        .footer-nav-item.active {
            color: var(--primary);
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

        .notification-badge {
    background: red;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    margin-left: 5px;
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
    <div class="main-content">
        <div class="dashboard-header">
            <div class="search-bar">
                <i class="fas fa-search" style="color: var(--gray); margin-right: 0.5rem;"></i>
                <input type="text" placeholder="Search...">
            </div>
            
            <div class="user-profile">
                <div class="profile-img">JD</div>
                <span><?php echo htmlspecialchars($user_data['full_name']); ?></span>
            </div>
        </div>

        <h1>Welcome to SI QR Info Hub</h1>
        <p>Quickly access your most used features below</p><br>

        <div class="menu-grid" id="menuGrid">
            <!-- Menu items will be populated by JavaScript -->
        </div>
        
        <!-- News and Updates Section -->
        <div class="news-section">
            <h2>News and Updates</h2>
            
            <div class="news-item">
                <h3>New Housing Policy Announcement</h3>
                <p>The administration has released new guidelines for housing allocations. Please review them carefully.</p>
                <div class="date">May 15, 2023</div>
            </div>
            
            <div class="news-item">
                <h3>Upcoming Community Event</h3>
                <p>Join us for the annual community gathering on June 10th at the central plaza.</p>
                <div class="date">May 10, 2023</div>
            </div>
            
            <div class="news-item">
                <h3>Maintenance Schedule</h3>
                <p>Scheduled maintenance for Block A will occur next week. Please plan accordingly.</p>
                <div class="date">May 5, 2023</div>
            </div>
        </div>
    </div>

    <!-- Mobile Footer Navigation -->
    <div class="footer-nav">
        <div class="footer-nav-items">
            <a href="home.php" class="footer-nav-item active" id="nav-home-mobile">
                <span class="icon"><i class="fas fa-home"></i></span>
                <span>Home</span>
            </a>
            <a href="spa.php?page=notifications" class="footer-nav-item" id="nav-community-mobile">
                <span class="icon"><i class="fa-solid fa-bell"></i></span>
                <span>Notification</span>
            </a>
            <a href="spa.php?page=profile" class="footer-nav-item" id="nav-profile-mobile">
                <span class="icon"><i class="fas fa-user"></i></span>
                <span>Profile</span>
            </a>
            <a href="spa.php?page=settings" class="footer-nav-item" id="nav-settings-mobile">
                <span class="icon"><i class="fa-solid fa-sliders"></i></span>
                <span>Settings</span>
            </a>
            <a href="spa.php?page=regulations" class="footer-nav-item" id="nav-events-mobile">
                <span class="icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
                <span>Regulations</span>
            </a>
        </div>
    </div>

    <script>
        // Collapse sidebar after clicking a nav item (desktop only)
        if (window.innerWidth > 768) {
            const sidebar = document.getElementById('sidebar');
            const navItems = document.querySelectorAll('.sidebar-nav-item');
            
            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    sidebar.classList.add('sidebar-collapsed');
                    setTimeout(() => {
                        sidebar.classList.remove('sidebar-collapsed');
                    }, 500);
                });
            });
        }

        // Highlight active nav item in mobile footer
        const currentPage = window.location.pathname.split('/').pop() || 'home.php';
        const mobileNavItems = document.querySelectorAll('.footer-nav-item');
        
        mobileNavItems.forEach(item => {
            if (item.getAttribute('href') === currentPage) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuData = [
                { href: 'housing.php', icon: 'fa-home', title: 'Housing', message: 'Find your perfect home.' },
                { href: 'Events.php', icon: 'fa-calendar-alt', title: 'Events', message: 'Discover upcoming events.' },
                { href: 'Community.php', icon: 'fa-users', title: 'Community', message: 'Connect with others nearby.' },
                { href: 'rest/index.php', icon: 'fa-concierge-bell', title: 'Restaurant', message: 'Explore Restaurant in Town.' },
                { href: 'Jobs/index.php', icon: 'fa-solid fa-briefcase', title: 'Jobs & Gigs', message: 'Browse all Jobs in the city.' },
                { href: 'rest/trans/index.php', icon: 'fas fa-car', title: 'Transportation', message: 'Check and Understand Transport in the city.' },
                { href: 'rest/hos/index.php', icon: 'fa-solid fa-hospital', title: 'Emergency/Hospital', message: 'Get Emergency Helps here.' },
                { href: 'rest/market/index.php', icon: 'fa-solid fa-cart-plus', title: 'Shopping', message: 'See Shopping mall and market.' },
                { href: 'rest/school/index.php', icon: 'fa-solid fa-school', title: 'Schools', message: 'Find schools and University.' },
                { href: 'Waste.php', icon: 'fas fa-calendar', title: 'Waste Managment', message: 'View Waste Managment Calendar.' }
            ];
            
            const menuGrid = document.getElementById('menuGrid');
            
            menuData.forEach(item => {
                const a = document.createElement('a');
                a.href = item.href;
                a.className = 'menu-item';
                a.innerHTML = `
                    <i class="fas ${item.icon}"></i>
                    <h3>${item.title}</h3>
                    <p>${item.message}</p>
                `;
                menuGrid.appendChild(a);
            });
        });
    </script>
</body>
</html>
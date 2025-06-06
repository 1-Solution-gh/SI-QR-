<?php
require_once 'connection.php';

$today = date('Y-m-d');
$dayOfWeek = date('l');

$todayCollections = [];
$upcomingCollections = [];
$recyclingCenters = [];

// Today's collections
$sqlToday = "SELECT * FROM waste_management WHERE schedule_date = ? ORDER BY schedule_time";
$stmtToday = $conn->prepare($sqlToday);
if ($stmtToday) {
    $stmtToday->bind_param('s', $today);
    $stmtToday->execute();
    $result = $stmtToday->get_result();
    while ($row = $result->fetch_assoc()) {
        $todayCollections[] = $row;
    }
    $stmtToday->close();
} else {
    die("Error fetching today's collections: " . $conn->error);
}

// Upcoming collections
$sqlUpcoming = "SELECT * FROM waste_management WHERE schedule_date > ? ORDER BY schedule_date, schedule_time LIMIT 3";
$stmtUpcoming = $conn->prepare($sqlUpcoming);
if ($stmtUpcoming) {
    $stmtUpcoming->bind_param('s', $today);
    $stmtUpcoming->execute();
    $result = $stmtUpcoming->get_result();
    while ($row = $result->fetch_assoc()) {
        $upcomingCollections[] = $row;
    }
    $stmtUpcoming->close();
} else {
    die("Error fetching upcoming collections: " . $conn->error);
}

// Recycling centers
$sqlRecycling = "SELECT * FROM recycling_centers";
$resultRecycling = $conn->query($sqlRecycling);
if ($resultRecycling) {
    while ($row = $resultRecycling->fetch_assoc()) {
        $recyclingCenters[] = $row;
    }
} else {
    die("Error fetching recycling centers: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SI QR Info Hun - Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="home.css">
  <link rel="stylesheet" href="menugrid.css">
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
        
        /* Add this to your existing styles */
.content-container {
    margin-left: var(--sidebar-width); /* Match the sidebar's collapsed width */
    padding: 20px;
    transition: var(--transition); /* Smooth transition when sidebar expands */
}

/* When sidebar is hovered and expands */
.sidebar-nav:hover ~ .content-container {
    margin-left: var(--sidebar-expanded);
}

/* For mobile, remove the margin */
@media (max-width: 768px) {
    .content-container {
        margin-left: 0;
    }
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
     <div class="logo" style="width: 40px; height: 40px; background: white; border-radius: 10px;"></div>
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
    <div class="dynamic-header">
        <div class="back-button"><a href="home.php" style="text-decoration: none;">←</a></div>
        <h2>🗑️ Waste Management</h2>
        <div class="current-date">Today: <?php echo $dayOfWeek . ', ' . date('F j'); ?></div>
    </div>
    
    <div class="waste-schedule">
        <!-- Today's Collection -->
        <?php if (!empty($todayCollections)): ?>
            <?php foreach ($todayCollections as $collection): ?>
            <div class="schedule-card today">
                <div class="waste-icon">
                    <?php echo $collection['collection_type'] === 'recycling' ? '♻️' : '🗑️'; ?>
                </div>
                <div>
                    <h3>Today's Collection</h3>
                    <p><?php echo ucfirst($collection['collection_type']); ?> • <?php echo $collection['schedule_time']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="schedule-card">
                <div class="waste-icon">🗑️</div>
                <div>
                    <h3>No collections today</h3>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Next Collections -->
        <?php foreach ($upcomingCollections as $collection): ?>
        <div class="schedule-card">
            <div class="waste-icon">
                <?php echo $collection['collection_type'] === 'recycling' ? '♻️' : '🗑️'; ?>
            </div>
            <div>
                <h3><?php echo date('l', strtotime($collection['schedule_date'])); ?></h3>
                <p><?php echo ucfirst($collection['collection_type']); ?> • <?php echo $collection['schedule_time']; ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (!empty($recyclingCenters)): ?>
    <div class="recycling-locations">
        <h3>♻️ Recycling Centers</h3>
        <?php foreach ($recyclingCenters as $center): ?>
        <div class="location-card">
            <h4><?php echo htmlspecialchars($center['name']); ?></h4>
            <p>📌 <?php echo htmlspecialchars($center['address']); ?> • <?php echo $center['is_24_hours'] ? 'Open 24/7' : htmlspecialchars($center['opening_hours']); ?></p>
            <button class="general-btn">Get Calendar</button>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
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
</body>
</html>
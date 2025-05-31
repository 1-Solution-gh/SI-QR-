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
        <h2 data-i18n="housing_title">🏡 Housing Options</h2>
        <div class="sort-filter">
            <select class="sort-select">
                <option data-i18n="price_low_high">Price: Low to High</option>
                <option data-i18n="price_high_low">Price: High to Low</option>
            </select>
        </div>
    </div>
    
    <div class="property-grid">
        <!-- Apartment Card -->
        <div class="property-card">
            <div class="badge" data-i18n="apartment">Apartment</div>
            <img src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80">
            <div class="property-info">
                <h3 data-i18n="sunny_loft">Sunny Downtown Loft</h3>
                <p class="price" data-i18n="price_1200">$1,200/mo</p>
                <div class="features">
                    <span data-i18n="beds">🛏️ 3 beds</span>
                    <span data-i18n="bath">🚿 2 bath</span>
                    <span data-i18n="sqft">📏 1000 sqft</span>
                </div>
                <button class="general-btn" data-i18n="contact_agent">Contact Agent</button>
            </div>
        </div>
    
        <!-- Apartment Card -->
        <div class="property-card">
            <div class="badge" data-i18n="apartment">Apartment</div>
            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80">
            <div class="property-info">
                <h3 data-i18n="sunny_loft">Sunny Downtown Loft</h3>
                <p class="price" data-i18n="price_1200">$1,200/mo</p>
                <div class="features">
                    <span data-i18n="beds">🛏️ 2 beds</span>
                    <span data-i18n="bath">🚿 1 bath</span>
                    <span data-i18n="sqft">📏 850 sqft</span>
                </div>
                <button class="general-btn" data-i18n="contact_agent">Contact Agent</button>
            </div>
        </div>
    
        <!-- Apartment Card -->
        <div class="property-card">
            <div class="badge" data-i18n="apartment">Apartment</div>
            <img src="https://images.unsplash.com/photo-1601918774946-25832a4be0d6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80">
            <div class="property-info">
                <h3 data-i18n="sunny_loft">Sunny Downtown Loft</h3>
                <p class="price" data-i18n="price_1200">$1,200/mo</p>
                <div class="features">
                    <span data-i18n="bed">🛏️ 1 beds</span>
                    <span data-i18n="bath">🚿 1 bath</span>
                    <span data-i18n="sqft">📏 600 sqft</span>
                </div>
                <button class="general-btn" data-i18n="contact_agent">Contact Agent</button>
            </div>
        </div>
        
        <!-- Hotel Card -->
        <div class="property-card">
            <div class="badge" data-i18n="hotel">Hotel</div>
            <img src="https://images.unsplash.com/photo-1600210492493-0946911123ea?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1974&q=80">
            <div class="property-info">
                <h3 data-i18n="city_hotel">City Center Hotel</h3>
                <p class="price" data-i18n="price_99">$99/night</p>
                <div class="features">
                    <span data-i18n="star">⭐ 4.5-star</span>
                    <span data-i18n="breakfast">🍳 Breakfast</span>
                    <span data-i18n="pool">🏊 Pool</span>
                </div>
                <button class="general-btn" data-i18n="book_now">Book Now</button>
            </div>
        </div>
    </div>
    
    <!-- Hotel Card -->
    <div class="property-card">
        <div class="badge" data-i18n="hotel">Hotel</div>
        <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80">
        <div class="property-info">
            <h3 data-i18n="ocean_villa">Oceanview Villa</h3>
            <p class="price" data-i18n="price_85">$85/night</p>
            <div class="features">
                <span data-i18n="star">⭐ 4.2-star</span>
                <span data-i18n="breakfast">🍳 Breakfast</span>
                <span data-i18n="pool">🏊 Pool</span>
            </div>
            <button class="general-btn" data-i18n="book_now">Book Now</button>
        </div>
    </div>
    </div>
    
    <!-- Hotel Card -->
    <div class="property-card">
        <div class="badge" data-i18n="hotel">Hotel</div>
        <img src="https://images.unsplash.com/photo-1605276374104-dee2a0ed3cd6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80">
        <div class="property-info">
            <h3 data-i18n="skyline_penthouse">Skyline Penthouse</h3>
            <p class="price" data-i18n="price_150">$150/night</p>
            <div class="features">
                <span data-i18n="star">⭐ 4-star</span>
                <span data-i18n="breakfast">🍳 Breakfast</span>
                <span data-i18n="pool">🏊 Pool</span>
            </div>
            <button class="general-btn" data-i18n="book_now">Book Now</button>
        </div>
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
</body>
</html>
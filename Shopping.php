<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SI QR Info Hun - Home</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="home.css">
  <link rel="stylesheet" href="menugrid.css">
</head>
<body>
  <div class="main-container">
    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
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
        <h2 data-i18n="shopping_title">🛍️ Shopping Guide</h2>
        <div class="search-bar">
            <input type="text" placeholder="Search stores..." data-i18n-placeholder="search_stores">
        </div>
    </div>
    
    <div class="shopping-categories">
        <button class="category-card" style="background: #FFF2F2;">
            <div class="icon">🛒</div>
            <h3 data-i18n="supermarkets">Supermarkets</h3>
        </button>
        
        <button class="category-card" style="background: #F0F7FF;">
            <div class="icon">👕</div>
            <h3 data-i18n="clothing">Clothing</h3>
        </button>
        
        <button class="category-card" style="background: #F5FFF0;">
            <div class="icon">📱</div>
            <h3 data-i18n="electronics">Electronics</h3>
        </button>
    
        <button class="category-card" style="background: #F5FFF0;">
           <div class="icon">🏚️</div>
           <h3 data-i18n="housing">Housing</h3>
        </button>
    </div>
    
    <div class="featured-stores">
        <h3 data-i18n="popular_stores">🌟 Popular Stores</h3>
        
        <div class="store-card">
            <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80">
            <div class="store-info">
                <h4 data-i18n="city_market">City Fresh Market</h4>
                <div class="store-meta">
                    <span data-i18n="grocery">🛒 Grocery</span>
                    <span>⭐ 4.5 (320)</span>
                </div>
                <button class="general-btn">
                    <i class="fas fa-directions"></i> <span data-i18n="km">0.7km</span>
                </button>
            </div>
        </div>
    
        <div class="store-card">
        <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80">
        <div class="store-info">
            <h4 data-i18n="walmart">Walmat</h4>
            <div class="store-meta">
                <span data-i18n="shopping_mall">🛒 Shopping Mall</span>
                <span>⭐ 4.3 (320)</span>
            </div>
            <button class="general-btn">
                <i class="fas fa-directions"></i> <span data-i18n="km">0.5km</span>
            </button>
        </div>
    </div>
    
    <div class="store-card">
    <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80">
    <div class="store-info">
        <h4 data-i18n="carrefour">Carrefour</h4>
        <div class="store-meta">
            <span data-i18n="grocery_cold">🛒 Grocery & Cold Store</span>
            <span>⭐ 4.1 (320)</span>
        </div>
        <button class="general-btn">
            <i class="fas fa-directions"></i> <span data-i18n="km">2km</span>
        </button>
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
</body>

</html>
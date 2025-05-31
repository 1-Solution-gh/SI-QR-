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
    <h2 data-i18n="restaurants_title">Dining & Restaurants</h2>
    </div>
    
    <div class="restaurants-container">
    <div class="restaurant-filters">
        <div class="filter-chip active" data-i18n="all">All</div>
        <div class="filter-chip" data-i18n="italian">Italian</div>
        <div class="filter-chip" data-i18n="asian">Asian</div>
        <div class="filter-chip" data-i18n="vegan">Vegan</div>
        <div class="filter-chip" data-i18n="fast_food">Fast Food</div>
        <div class="filter-chip" data-i18n="cafes">Cafés</div>
    </div>
    
    <div class="restaurant-list">
        <div class="restaurant-card">
            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="restaurant-image" 
                 alt="La Bella Italia">
            <div class="restaurant-details">
                <h3 class="restaurant-name" data-i18n="bella_italia">La Bella Italia</h3>
                <span class="restaurant-cuisine" data-i18n="italian">Italian</span>
                <div class="restaurant-info">
                    <span><i class="fas fa-map-marker-alt"></i> <span data-i18n="km">1.2 km away</span></span>
                    <span><i class="fas fa-clock"></i> <span data-i18n="open_now">Open now</span></span>
                    <span><i class="fas fa-dollar-sign"></i> $$</span>
                </div>
                <div class="restaurant-rating">
                    <i class="fas fa-star"></i> 4.7 (128)
                </div>
            </div>
        </div>
        
        <div class="restaurant-card">
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="restaurant-image" 
                 alt="Tokyo Sushi">
            <div class="restaurant-details">
                <h3 class="restaurant-name" data-i18n="tokyo_sushi">Tokyo Sushi</h3>
                <span class="restaurant-cuisine" data-i18n="japanese">Japanese</span>
                <div class="restaurant-info">
                    <span><i class="fas fa-map-marker-alt"></i> <span data-i18n="km">0.8 km away</span></span>
                    <span><i class="fas fa-clock"></i> <span data-i18n="closes_10pm">Closes at 10 PM</span></span>
                    <span><i class="fas fa-dollar-sign"></i> $$$</span>
                </div>
                <div class="restaurant-rating">
                    <i class="fas fa-star"></i> 4.9 (245)
                </div>
            </div>
        </div>
    
        <div class="restaurant-card">
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="restaurant-image" 
                 alt="Tokyo Sushi">
            <div class="restaurant-details">
                <h3 class="restaurant-name" data-i18n="french_taste">French Taste</h3>
                <span class="restaurant-cuisine" data-i18n="french">France</span>
                <div class="restaurant-info">
                    <span><i class="fas fa-map-marker-alt"></i> <span data-i18n="km">2 km away</span></span>
                    <span><i class="fas fa-clock"></i> <span data-i18n="closes_12am">Closes at 12 AM</span></span>
                    <span><i class="fas fa-dollar-sign"></i> $$$</span>
                </div>
                <div class="restaurant-rating">
                    <i class="fas fa-star"></i> 4.9 (245)
                </div>
            </div>
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
</body>

</html>
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
        <h2 data-i18n="schools_title">🏫 Schools & Education</h2>
        <select class="grade-filter">
            <option data-i18n="all_grades">All Grades</option>
            <option data-i18n="elementary">Elementary</option>
            <option data-i18n="high_school">High School</option>
        </select>
    </div>
    
    <div class="schools-map">
        <img src="img/maps.jpg" alt="Schools Map" style="width=100%">
    </div>
    
    <div class="school-cards">
        <!-- International School -->
        <div class="school-card">
            <div class="school-type" data-i18n="international">🌍 International</div>
            <h3 data-i18n="global_academy">Global Academy</h3>
            <div class="school-details">
                <p data-i18n="bus">📚 K-12 • 🚌 School Bus</p>
                <p data-i18n="miles">📍 2.3 miles away</p>
            </div>
            <button class="general-btn" data-i18n="visit_website">Visit Website</button>
        </div>
        
        <!-- Language School -->
        <div class="school-card">
            <div class="school-type" data-i18n="language">🗣️ Language</div>
            <h3 data-i18n="language_center">City Language Center</h3>
            <div class="school-details">
                <p data-i18n="painting_course">📚 Painting Course• 🕒 EveningClasses</p>
                <p data-i18n="downtown">📍 Downtown</p>
            </div>
            <button class="general-btn" data-i18n="visit_website">Visit Website</button>
        </div>
    
        <!-- art School -->
        <div class="school-card">
            <div class="school-type" data-i18n="art">🎨 Art</div>
            <h3 data-i18n="art_optic">The Art Optic</h3>
            <div class="school-details">
                <p data-i18n="adult_courses">📚 Adult Courses • 🕒 Morning/Evening Classes</p>
                <p data-i18n="miles">📍 2.0 miles away</p>
            </div>
            <button class="general-btn" data-i18n="visit_website">Visit Website</button>
        </div>
    </div>
    
    <!-- art School -->
    <div class="school-card">
        <div class="school-type" data-i18n="university">🏢 University</div>
        <h3 data-i18n="plazdel_uni">PlazDel University</h3>
        <div class="school-details">
            <p data-i18n="courses">📚 200+ Courses • 🕒 Morning/Evening Classes</p>
            <p data-i18n="miles">📍 2.0 miles away</p>
        </div>
        <button class="general-btn" data-i18n="visit_website">Visit Website</button>
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
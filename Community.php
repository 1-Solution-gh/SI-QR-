<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Community & Threads</title>
  <style>
    /* Reset & Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }
    
    body {
      background-color: #f5f5f5;
      color: #333;
    }
    
    /* Main Container */
    .main-container {
      display: flex;
      height: 100vh;
    }
    
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
    
    /* Main Content */
    .content-container {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    
    /* Header */
    .dynamic-header {
      display: flex;
      align-items: center;
      padding: 15px;
      background: white;
      border-bottom: 1px solid #eee;
    }
    
    .back-button {
      margin-right: 15px;
      font-size: 20px;
    }
    
    .dynamic-header h2 {
      flex: 1;
      font-size: 20px;
    }
    
    /* Tabs */
    .community-tabs {
      display: flex;
      background: white;
      border-bottom: 1px solid #eee;
    }
    
    .tab-btn {
      flex: 1;
      padding: 15px;
      border: none;
      background: none;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
    }
    
    .tab-btn.active {
      border-bottom: 3px solid #3498db;
      color: #3498db;
    }
    
    /* Tab Content */
    .tab-content {
      display: none;
      flex: 1;
      overflow-y: auto;
      padding-bottom: 80px; /* Space for input box */
    }
    
    .tab-content.active {
      display: block;
    }
    
    /* Threads Screen */
    .threads-container {
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    
    .threads-list {
      flex: 1;
      padding: 15px;
    }
    
    .thread-item {
      background: white;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .thread-author {
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .thread-time {
      font-size: 12px;
      color: #999;
      margin-top: 10px;
    }
    
    /* Thread Input */
    .thread-input-container {
      position: fixed;
      bottom: 0;
      left: 80px;
      right: 0;
      background: white;
      padding: 15px;
      border-top: 1px solid #eee;
    }
    
    .thread-input-box {
      display: flex;
      align-items: flex-end;
    }
    
    .thread-input-box textarea {
      flex: 1;
      border: 1px solid #ddd;
      border-radius: 20px;
      padding: 10px 15px;
      resize: none;
      max-height: 100px;
      outline: none;
    }
    
    .thread-input-actions {
      display: flex;
      margin-left: 10px;
    }
    
    .media-upload-btn {
      background: none;
      border: none;
      font-size: 20px;
      color: #3498db;
      margin-right: 10px;
      cursor: pointer;
    }
    
    .send-thread-btn {
      background: #3498db;
      color: white;
      border: none;
      border-radius: 20px;
      padding: 10px 20px;
      cursor: pointer;
    }
    
    .thread-note {
      display: block;
      margin-top: 5px;
      font-size: 12px;
      color: #999;
      text-align: center;
    }

    /* Ensure threads container has proper spacing */
.threads-container {
  display: flex;
  flex-direction: column;
  height: calc(100vh - 150px); /* Adjust based on your header height */
  position: relative;
}

/* Threads list should scroll */
.threads-list {
  flex: 1;
  overflow-y: auto;
  padding: 15px;
  padding-bottom: 80px; /* Space for input box */
}

/* Fixed input container */
.thread-input-container {
  position: fixed;
  bottom: 0;
  left: 80px; /* Match sidebar width */
  right: 0;
  background: white;
  padding: 15px;
  border-top: 1px solid #eee;
  z-index: 10;
}

/* Adjust for mobile */
@media (max-width: 768px) {
  .thread-input-container {
    left: 0;
    bottom: 60px; /* Above mobile footer */
  }
  
  .threads-list {
    padding-bottom: 140px; /* Extra space for mobile */
  }
}
    
    /* Communities Screen */
    .communities-container {
      padding: 15px;
    }
    
    .create-community-btn {
      background: #3498db;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 10px 15px;
      margin-bottom: 15px;
      cursor: pointer;
    }
    
    .group-filters {
      display: flex;
      overflow-x: auto;
      margin-bottom: 15px;
      padding-bottom: 5px;
    }
    
    .filter {
      background: white;
      border: 1px solid #ddd;
      border-radius: 20px;
      padding: 8px 15px;
      margin-right: 10px;
      white-space: nowrap;
      cursor: pointer;
    }
    
    .filter.active {
      background: #3498db;
      color: white;
      border-color: #3498db;
    }
    
    .group-card {
      background: white;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .group-avatar {
      font-size: 30px;
      margin-right: 15px;
    }
    
    .group-info {
      flex: 1;
    }
    
    .group-info h3 {
      margin-bottom: 5px;
    }
    
    .group-info p {
      font-size: 14px;
      color: #666;
      margin-bottom: 5px;
    }
    
    .group-tags span {
      display: inline-block;
      background: #eee;
      border-radius: 3px;
      padding: 3px 8px;
      font-size: 12px;
      margin-right: 5px;
      color: #555;
    }
    
    .join-btn {
      background: #2ecc71;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 8px 15px;
      cursor: pointer;
    }
    
    /* Create Community Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 80px;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      z-index: 100;
      align-items: center;
      justify-content: center;
    }
    
    .modal-content {
      background: white;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      padding: 20px;
    }
    
    .close-modal {
      float: right;
      font-size: 24px;
      cursor: pointer;
    }
    
    .modal h3 {
      margin-bottom: 20px;
      clear: both;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    
    .form-group textarea {
      min-height: 100px;
    }
    
    /* Mobile Footer */
    .footer-nav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: white;
      border-top: 1px solid #eee;
      z-index: 10;
    }
    
    .footer-nav-items {
      display: flex;
      justify-content: space-around;
    }
    
    .footer-nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px 0;
      text-decoration: none;
      color: #333;
      font-size: 12px;
    }
    
    .footer-nav-item.active {
      color: #3498db;
    }
    
    .footer-nav-item .icon {
      font-size: 20px;
      margin-bottom: 3px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .sidebar-nav {
        display: none;
      }
      
      .thread-input-container {
        left: 0;
      }
      
      .modal {
        left: 0;
      }
      
      .footer-nav {
        display: block;
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
        <div class="back-button"><a href="home.php" style="text-decoration: none; color: inherit;">←</a></div>
        <h2>Community</h2>
      </div>
      
      <!-- Tab Navigation -->
      <div class="community-tabs">
        <button class="tab-btn active" data-tab="threads">Threads</button>
        <button class="tab-btn" data-tab="communities">Communities</button>
      </div>
      
      <!-- Threads Tab Content -->
      <div id="threads-tab" class="tab-content active">
        <div class="threads-container">
          <div class="threads-list">
            <!-- Sample Thread Items -->
            <div class="thread-item">
              <div class="thread-author">John Doe</div>
              <div class="thread-content">Hey everyone! Just joined this community. Looking forward to connecting with you all!</div>
              <div class="thread-time">2 hours ago</div>
            </div>
            
            <div class="thread-item">
              <div class="thread-author">Jane Smith</div>
              <div class="thread-content">Does anyone know good places to eat around here?</div>
              <div class="thread-time">5 hours ago</div>
            </div>
            
            <div class="thread-item">
              <div class="thread-author">Mike Johnson</div>
              <div class="thread-content">Check out this cool video I took yesterday!</div>
              <div class="thread-media"><img src="https://via.placeholder.com/300x200" alt="Video thumbnail" style="width:100%; border-radius:8px;"></div>
              <div class="thread-time">1 day ago</div>
            </div>
          </div>
        </div>
        
        <!-- Thread Input Box (Fixed at bottom) -->
        <div class="thread-input-container">
          <div class="thread-input-box">
            <textarea placeholder="Write your message..." rows="1"></textarea>
            <div class="thread-input-actions">
              <label class="media-upload-btn">
                <i class="fas fa-camera"></i>
                <input type="file" accept="image/*,video/*" style="display: none;">
              </label>
              <button class="send-thread-btn">Send</button>
            </div>
          </div>
          <small class="thread-note">Messages disappear after 3 days. Videos max 15 seconds.</small>
        </div>
      </div>
      
      <!-- Communities Tab Content -->
      <div id="communities-tab" class="tab-content">
        <div class="communities-container">
          <button class="create-community-btn" id="create-community-btn">+ Create Community</button>
          
          <div class="group-filters">
            <button class="filter active" data-filter="all">All</button>
            <button class="filter" data-filter="family">👨‍👩‍👧‍👦 Family</button>
            <button class="filter" data-filter="arts">🎨 Arts</button>
            <button class="filter" data-filter="sports">🏀 Sports</button>
            <button class="filter" data-filter="food">🍔 Food</button>
            <button class="filter" data-filter="music">🎵 Music</button>
          </div>
          
          <div class="groups-list">
            <!-- Sample Community Cards -->
            <div class="group-card">
              <div class="group-avatar">👶</div>
              <div class="group-info">
                <h3>New Parents Network</h3>
                <p>👋 128 members • Weekly meetups</p>
                <div class="group-tags">
                  <span>Family</span>
                  <span>Support</span>
                </div>
              </div>
              <button class="join-btn">Join</button>
            </div>
            
            <div class="group-card">
              <div class="group-avatar">🗣️</div>
              <div class="group-info">
                <h3>Language Exchange</h3>
                <p>🌍 64 members • Every Thursday</p>
                <div class="group-tags">
                  <span>Learning</span>
                  <span>Social</span>
                </div>
              </div>
              <button class="join-btn">Join</button>
            </div>
            
            <div class="group-card">
              <div class="group-avatar">⚾</div>
              <div class="group-info">
                <h3>Golf and Gym</h3>
                <p>🌍 23 members • Every Saturday</p>
                <div class="group-tags">
                  <span>Sport</span>
                  <span>Golf</span>
                </div>
              </div>
              <button class="join-btn">Join</button>
            </div>
            
            <div class="group-card">
              <div class="group-avatar">🎤</div>
              <div class="group-info">
                <h3>Karaoke Nights</h3>
                <p>🌍 87 members • Every Sunday</p>
                <div class="group-tags">
                  <span>Singing</span>
                  <span>Dancing</span>
                </div>
              </div>
              <button class="join-btn">Join</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Community Modal -->
  <div class="modal" id="create-community-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h3>Create New Community</h3>
      <form id="community-form">
        <div class="form-group">
          <label>Community Name</label>
          <input type="text" placeholder="e.g. Food Lovers Club" required>
        </div>
        
        <div class="form-group">
          <label>Description</label>
          <textarea placeholder="What's your community about?" required></textarea>
        </div>
        
        <div class="form-group">
          <label>Category</label>
          <select required>
            <option value="">Select a category</option>
            <option value="family">Family</option>
            <option value="arts">Arts</option>
            <option value="sports">Sports</option>
            <option value="food">Food</option>
            <option value="music">Music</option>
            <option value="other">Other</option>
          </select>
        </div>
        
        <div class="form-group">
          <label>Community Image</label>
          <input type="file" accept="image/*">
        </div>
        
        <div class="form-group">
          <label>Privacy</label>
          <div>
            <label><input type="radio" name="privacy" value="public" checked> Public (Anyone can join)</label>
            <label><input type="radio" name="privacy" value="private"> Private (Admin approval required)</label>
          </div>
        </div>
        
        <button type="submit" class="create-community-btn" style="width:100%;">Submit for Approval</button>
      </form>
    </div>
  </div>

  <!-- Mobile Footer Navigation -->
  <div class="footer-nav">
    <div class="footer-nav-items">
      <a href="home.php" class="footer-nav-item" id="nav-home-mobile">
        <span class="icon"><i class="fas fa-home"></i></span>
        <span>Home</span>
      </a>
      <a href="spa.php?page=notifications" class="footer-nav-item" id="nav-community-mobile">
        <span class="icon"><i class="fa-solid fa-bell"></i></span>
        <span>Notification</span>
      </a>
      <a href="spa.php?page=profile" class="footer-nav-item active" id="nav-profile-mobile">
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

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    // Tab switching functionality
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        // Update active tab button
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        // Show corresponding tab content
        const tabId = btn.getAttribute('data-tab');
        document.querySelectorAll('.tab-content').forEach(content => {
          content.classList.remove('active');
        });
        document.getElementById(`${tabId}-tab`).classList.add('active');
      });
    });
    
    // Create community modal
    const modal = document.getElementById('create-community-modal');
    const createBtn = document.getElementById('create-community-btn');
    const closeBtn = document.querySelector('.close-modal');
    
    createBtn.addEventListener('click', () => {
      modal.style.display = 'flex';
    });
    
    closeBtn.addEventListener('click', () => {
      modal.style.display = 'none';
    });
    
    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
    
    // Form submission
    document.getElementById('community-form').addEventListener('submit', (e) => {
      e.preventDefault();
      // Here you would send the data to the server
      alert('Community submitted for approval!');
      modal.style.display = 'none';
    });
    
    // Thread functionality
    const threadInput = document.querySelector('.thread-input-box textarea');
    const sendThreadBtn = document.querySelector('.send-thread-btn');
    
    sendThreadBtn.addEventListener('click', () => {
      const message = threadInput.value.trim();
      if (message) {
        // Here you would send the message to the server
        console.log('Message sent:', message);
        threadInput.value = '';
        
        // Add message to threads list (temporary, would come from server in real app)
        const threadsList = document.querySelector('.threads-list');
        const newThread = document.createElement('div');
        newThread.className = 'thread-item';
        newThread.innerHTML = `
          <div class="thread-author">You</div>
          <div class="thread-content">${message}</div>
          <div class="thread-time">Just now</div>
        `;
        threadsList.prepend(newThread);
      }
    });
    
    // Auto-expand textarea as user types
    threadInput.addEventListener('input', function() {
      this.style.height = 'auto';
      this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Media upload handling
    const mediaUpload = document.querySelector('.media-upload-btn input');
    mediaUpload.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (file) {
        if (file.type.includes('video') && file.size > 15000000) { // ~15MB for 15s video
          alert('Video too large. Maximum 15 seconds.');
          return;
        }
        
        // Here you would upload the file and get a URL
        console.log('Media selected:', file.name);
        
        // For demo purposes, we'll just show a preview
        const reader = new FileReader();
        reader.onload = function(event) {
          const threadsList = document.querySelector('.threads-list');
          const newThread = document.createElement('div');
          newThread.className = 'thread-item';
          
          if (file.type.includes('image')) {
            newThread.innerHTML = `
              <div class="thread-author">You</div>
              <div class="thread-content">Check out this image!</div>
              <div class="thread-media"><img src="${event.target.result}" style="width:100%; border-radius:8px;"></div>
              <div class="thread-time">Just now</div>
            `;
          } else if (file.type.includes('video')) {
            newThread.innerHTML = `
              <div class="thread-author">You</div>
              <div class="thread-content">Check out this video!</div>
              <div class="thread-media">
                <video controls style="width:100%; border-radius:8px;">
                  <source src="${event.target.result}" type="${file.type}">
                  Your browser does not support the video tag.
                </video>
              </div>
              <div class="thread-time">Just now</div>
            `;
          }
          
          threadsList.prepend(newThread);
        };
        reader.readAsDataURL(file);
      }
    });
    
    // Filter communities
    document.querySelectorAll('.filter').forEach(filter => {
      filter.addEventListener('click', () => {
        document.querySelectorAll('.filter').forEach(f => f.classList.remove('active'));
        filter.classList.add('active');
        
        // In a real app, you would filter the communities here
        console.log('Filter by:', filter.getAttribute('data-filter'));
      });
    });
    
    // Join community buttons
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('join-btn')) {
        e.target.textContent = 'Joined';
        e.target.style.background = '#95a5a6';
        e.target.disabled = true;
        // In a real app, you would send a request to join the community
      }
    });
  </script>
</body>
</html>
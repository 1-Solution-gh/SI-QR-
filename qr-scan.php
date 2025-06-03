<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SI QR Info Hub - Registration</title>
  <link rel="stylesheet" href="qr.css">
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

/* Logo & Branding */
.logo {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary), var(--accent));
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}

.logo::after {
    content: '';
    position: absolute;
    width: 150%;
    height: 150%;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 70%);
    animation: rotate 10s linear infinite;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.logo-inner {
    width: 70%;
    height: 70%;
    background-color: white;
    border-radius: 8px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(3, 1fr);
    padding: 6px;
    z-index: 2;
}

.logo-dot {
    background-color: var(--primary);
    border-radius: 2px;
}

.app-name {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(to right, var(--primary), var(--accent));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
  </style>
  <script>
    function loadBlinkIdSDK() {
      return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/@microblink/blinkid-in-browser@5.11.0/dist/blinkid-in-browser.min.js';
        script.onload = resolve;
        script.onerror = () => reject(new Error('Failed to load BlinkID SDK'));
        document.head.appendChild(script);
      });
    }
  </script>
  <script src="scanning.js" defer></script>
</head>
<body>
  <div class="app-container">

    <main class="app-content">
    <div class="screen" id="signup">
    <center>
    <h1 class="app-name animate-fade">SI QR INFO HUB</h1>
    
    <div class="logo">
        <div class="logo-inner">
            <div class="logo-dot" style="grid-column: 1; grid-row: 1;"></div>
            <div class="logo-dot" style="grid-column: 3; grid-row: 1;"></div>
            <div class="logo-dot" style="grid-column: 2; grid-row: 2;"></div>
            <div class="logo-dot" style="grid-column: 1; grid-row: 3;"></div>
            <div class="logo-dot" style="grid-column: 3; grid-row: 3;"></div>
        </div>
    </div>
  </center>

      <!-- QR Scanning Section - Hidden on desktop by default -->
      <section class="qr-section card" id="qrScreen">
        <div class="section-header">
          <svg class="qr-icon" viewBox="0 0 24 24">
            <path fill="currentColor" d="M3,11H5V13H3V11M11,5H13V9H11V5M9,11H13V15H11V13H9V11M15,11H17V13H19V11H21V13H19V15H21V19H19V21H17V19H13V21H11V17H15V15H17V13H15V11M19,19V15H17V19H19M15,3H21V9H15V3M17,5V7H19V5H17M3,3H9V9H3V3M5,5V7H7V5H5M3,15H9V21H3V15M5,17V19H7V17H5Z" />
          </svg>
          <h2>Scan Your Document</h2>
          <p class="subtitle">Use your camera to scan QR code or passport</p>
        </div>
        
        <div class="qr-content">
          <div class="action-buttons">
            <button class="btn btn-primary animate-slide delay-2" id="start-scan" style="width:100%;">
              <svg class="btn-icon" viewBox="0 0 24 24">
                <path fill="currentColor" d="M4,4H7L9,2H15L17,4H20A2,2 0 0,1 22,6V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6A2,2 0 0,1 4,4M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z" />
              </svg>
              Scan Now
            </button>
          </div>
          
          <div class="divider">
            <span>or</span>
          </div>
          
          <button class="btn btn-secondary animate-slide delay-3"  id="manualFillBtn"  style="width:100%;">
            <svg class="btn-icon" viewBox="0 0 24 24">
              <path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
            </svg>
            Enter Details Manually
          </button>
        </div>
        
        <div id="result" class="scan-result"></div>
      </section>

      <!-- Form Section - Shown by default on desktop -->
      <section class="form-section card" id="formSection">
        <div class="section-header">
          <h2>Registration Form</h2>
          <p class="subtitle">Please fill in your details</p>
        </div>
        
        <form id="userForm" method="post" action="manual_registration.php" class="registration-form">
          <div class="form-row">
            <div class="form-group">
              <label for="fullname">Full Name</label>
              <input type="text" name="fullname" id="fullname" required placeholder="John Doe">
            </div>
          </div>
          
          <div class="form-row dual-inputs">
            <div class="form-group">
              <label for="dob">Date of Birth</label>
              <input type="date" name="dob" id="dob" required>
            </div>
            
            <div class="form-group">
              <label for="gender">Gender</label>
              <select id="gender" name="gender" required>
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
          </div>
          
          <div class="form-row dual-inputs">
            <div class="form-group">
              <label for="passport">Passport Number</label>
              <input type="text" name="passportnumber" id="passport" required placeholder="AB1234567">
            </div>
            
            <div class="form-group">
              <label for="nationality">Nationality</label>
              <input type="text" name="nationality" id="nationality" required placeholder="Country">
            </div>
          </div>
          
          <div class="form-row dual-inputs">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" required placeholder="your@email.com">
            </div>
            
            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" name="tel" id="phone" required placeholder="+1234567890">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group terms-container">
              <div class="checkbox-wrapper">
                <input type="checkbox" id="terms-check" required>
                <label for="terms-check" class="checkbox-label">
                  I agree to the <a href="#" class="link">Terms</a> and <a href="#" class="link">Privacy Policy</a>
                </label>
              </div>
            </div>
          </div>
          
          <div class="form-actions">
            <button type="button" class="btn text-btn" onclick="showScanInstead()">
              <svg class="btn-icon" viewBox="0 0 24 24">
                <path fill="currentColor" d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z" />
              </svg>
              Back to Scan
            </button>
            <button type="submit" class="btn primary-btn submit-btn">
              Submit
              <svg class="btn-icon" viewBox="0 0 24 24">
                <path fill="currentColor" d="M2,21L23,12L2,3V10L17,12L2,14V21Z" />
              </svg>
            </button>
          </div>
        </form>
      </section>

      <!-- Dialog Box -->
      <div class="dialog-overlay" id="scanDialog">
        <div class="dialog-box card">
          <h3>Scanning Issue</h3>
          <p>We couldn't scan your document. Please try again or enter details manually.</p>
          <div class="dialog-actions">
            <button class="btn secondary-btn" onclick="retryScan()">Try Again</button>
            <button class="btn primary-btn" onclick="showFormInstead()">Enter Manually</button>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    document.getElementById('manualFillBtn').addEventListener('click', function() {
      document.getElementById('qrScreen').style.display = 'none';
      document.getElementById('formSection').style.display = 'block';
    });

    function showScanInstead() {
      document.getElementById('formSection').style.display = 'none';
      document.getElementById('qrScreen').style.display = 'block';
    }

    // Default view based on screen size
    function setInitialView() {
      if (window.innerWidth >= 768) {
        document.getElementById('qrScreen').style.display = 'none';
        document.getElementById('formSection').style.display = 'block';
      } else {
        document.getElementById('qrScreen').style.display = 'block';
        document.getElementById('formSection').style.display = 'none';
      }
    }

    // Set initial view and update on resize
    setInitialView();
    window.addEventListener('resize', setInitialView);
  </script>
</body>
</html>
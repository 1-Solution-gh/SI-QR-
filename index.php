<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>SI QR Info Hub - Welcome</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="index.css">
</head>
<body>
<div class="app-container">
        <!-- Splash Screen -->
        <div class="screen active" id="splash">
            <div class="logo">
                <div class="logo-inner">
                    <div class="logo-dot" style="grid-column: 1; grid-row: 1;"></div>
                    <div class="logo-dot" style="grid-column: 3; grid-row: 1;"></div>
                    <div class="logo-dot" style="grid-column: 2; grid-row: 2;"></div>
                    <div class="logo-dot" style="grid-column: 1; grid-row: 3;"></div>
                    <div class="logo-dot" style="grid-column: 3; grid-row: 3;"></div>
                </div>
            </div>
            <h1 class="app-name animate-fade">SI QR INFO HUB</h1>
            
            <div class="greeting-container">
                <div class="greeting-text" data-lang="en">Hello</div>
                <div class="greeting-text" data-lang="fr">Bonjour</div>
                <div class="greeting-text" data-lang="es">Hola</div>
                <div class="greeting-text" data-lang="de">Hallo</div>
                <div class="greeting-text" data-lang="it">Ciao</div>
                <div class="greeting-text" data-lang="ja">こんにちは</div>
            </div>
            
            <p class="animate-fade delay-1">Discover your city in style</p>
        </div>

        <!-- Language Selection -->
        <div class="screen" id="language">
            <div class="logo">
                <div class="logo-inner">
                    <div class="logo-dot" style="grid-column: 1; grid-row: 1;"></div>
                    <div class="logo-dot" style="grid-column: 3; grid-row: 1;"></div>
                    <div class="logo-dot" style="grid-column: 2; grid-row: 2;"></div>
                    <div class="logo-dot" style="grid-column: 1; grid-row: 3;"></div>
                    <div class="logo-dot" style="grid-column: 3; grid-row: 3;"></div>
                </div>
            </div>
            <h1 class="app-name animate-fade">SI QR INFO HUB</h1>
            <div class="greeting-container">
                <div class="greeting-text" data-lang="en">Hello</div>
                <div class="greeting-text" data-lang="fr">Bonjour</div>
                <div class="greeting-text" data-lang="es">Hola</div>
                <div class="greeting-text" data-lang="de">Hallo</div>
                <div class="greeting-text" data-lang="it">Ciao</div>
                <div class="greeting-text" data-lang="ja">こんにちは</div>
            </div>
            
            <div class="language-selector animate-slide delay-1">
                <select class="language-dropdown" id="languageDropdown">
                    <option value="">Choose your language</option>
                    <option value="en" data-icon="url(flags/uk.png)">English</option>
                    <option value="fr" data-icon="url(flags/france.png)">Français</option>
                    <option value="es" data-icon="url(flags/spain.png)">Español</option>
                    <option value="de" data-icon="url(flags/germany.png)">Deutsch</option>
                    <option value="it" data-icon="url(flags/italy.png)">Italiano</option>
                    <option value="ja" data-icon="url(flags/japan.png)">日本語</option>
                </select>
            </div>
            
            <button class="btn btn-primary animate-slide delay-2" id="continueBtn" disabled>Continue</button>
        </div>

        <!-- Auth Choice Screen - SIMPLE BUTTONS -->
<div class="screen" id="authChoice">
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
    <h2 class="animate-slide">Welcome to SI QR INFO HUB</h2>
    <p class="animate-fade delay-1">Please choose an option to continue</p>
    
    <div style="display: flex; flex-direction: column; gap: 1rem; width: 100%; max-width: 300px; margin-top: 2rem;">
        
        <a href="qr-scan.php" class="btn btn-secondary animate-slide delay-3" id="signupChoice" style="width: 100%;">
            Sign Up
        </a>

        <a href="login.php" class="btn btn-primary animate-slide delay-2" id="loginChoice" style="width: 100%;">
            Login
        </a>
    </div>
</div>

<script>
        // DOM Elements
        const screens = {
            splash: document.getElementById('splash'),
            language: document.getElementById('language'),
            authChoice: document.getElementById('authChoice'),
            login: document.getElementById('login'),
            signup: document.getElementById('signup'),
            welcome: document.getElementById('welcome')
        };

        const languageDropdown = document.getElementById('languageDropdown');
        const continueBtn = document.getElementById('continueBtn');
        const loginChoice = document.getElementById('loginChoice');
        const signupChoice = document.getElementById('signupChoice');
        const loginBtn = document.getElementById('loginBtn');
        const exploreBtn = document.getElementById('exploreBtn');
        const scanPassport = document.getElementById('scanPassport');
        const manualSignup = document.getElementById('manualSignup');
        const pinInputs = document.querySelectorAll('.pin-input');
        const greetingTexts = document.querySelectorAll('.greeting-text');

        // Show greeting animation
        function showGreetings() {
            let currentIndex = 0;
            
            function showNextGreeting() {
                // Hide all greetings
                greetingTexts.forEach(text => {
                    text.style.opacity = '0';
                });
                
                // Show current greeting
                greetingTexts[currentIndex].style.opacity = '1';
                
                // Move to next greeting
                currentIndex = (currentIndex + 1) % greetingTexts.length;
                
                // Schedule next change
                setTimeout(showNextGreeting, 1500);
            }
            
            // Start the cycle
            showNextGreeting();
        }

        // Switch screens with animation
        function switchScreen(current, next) {
            current.classList.remove('active');
            next.classList.add('active');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // Start greeting animation
            showGreetings();
            
            // Auto-advance from splash to language selection
            setTimeout(() => {
                switchScreen(screens.splash, screens.language);
            }, 3000);

            // Language selection
            languageDropdown.addEventListener('change', (e) => {
                continueBtn.disabled = !e.target.value;
            });

            // Continue button
            continueBtn.addEventListener('click', () => {
                switchScreen(screens.language, screens.authChoice);
            });

            // Auth choice selection
            loginChoice.addEventListener('click', () => {
                switchScreen(screens.authChoice, screens.login);
                pinInputs[0].focus();
            });

            signupChoice.addEventListener('click', () => {
                switchScreen(screens.authChoice, screens.signup);
            });

            // Login button
            loginBtn.addEventListener('click', () => {
                switchScreen(screens.login, screens.welcome);
            });

            // Signup options
            scanPassport.addEventListener('click', () => {
                switchScreen(screens.signup, screens.welcome);
            });

            manualSignup.addEventListener('click', () => {
                switchScreen(screens.signup, screens.welcome);
            });

            // Explore button
            exploreBtn.addEventListener('click', () => {
                alert('Premium city guide functionality would launch here!');
            });

            // PIN input handling
            pinInputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.target.value.length === 1) {
                        if (index < pinInputs.length - 1) {
                            pinInputs[index + 1].focus();
                        }
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value.length === 0) {
                        if (index > 0) {
                            pinInputs[index - 1].focus();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
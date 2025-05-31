<?php
session_start();

if (!isset($_SESSION['counter'])) {
    $_SESSION['counter'] = 0;
    echo "Session started!<br>";
} else {
    $_SESSION['counter']++;
}

echo "Session ID: " . session_id() . "<br>";
echo "Counter: " . $_SESSION['counter'] . "<br>";
echo "<pre>Session data: ";
print_r($_SESSION);
echo "</pre>";

// Test cookie
setcookie('test_cookie', 'value', time()+3600, '/');
echo "Cookies: ";
print_r($_COOKIE);
?>
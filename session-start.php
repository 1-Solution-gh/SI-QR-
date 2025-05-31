<?php
// session_check.php
session_start();

echo "<pre>";
echo "Session Status: " . session_status() . "\n";
echo "Session ID: " . session_id() . "\n";
echo "Session Data: ";
print_r($_SESSION);
echo "Cookies: ";
print_r($_COOKIE);
echo "</pre>";

if (isset($_SESSION['user_id'])) {
    echo "User is logged in (ID: " . $_SESSION['user_id'] . ")";
} else {
    echo "User is NOT logged in";
}
?>
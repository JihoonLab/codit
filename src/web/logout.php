<?php
include("./include/db_info.inc.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
unset($_SESSION[$OJ_NAME . '_' . 'user_id']);
setcookie($OJ_NAME . "_user", "", ['expires' => 0, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
setcookie($OJ_NAME . "_check", "", ['expires' => 0, 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
session_destroy();
header("Location:loginpage.php");
?>

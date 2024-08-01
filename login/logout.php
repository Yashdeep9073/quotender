<?php
session_start();

unset($_SESSION["login_user"]);
unset($_SESSION["login_user_id"]);
// session_destroy();


header('Location: index.php');
?>
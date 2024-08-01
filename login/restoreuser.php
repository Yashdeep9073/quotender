<?php

session_start();
include("db/config.php");

// tender sent
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql8 = "UPDATE user_tender_requests set delete_tender = '0' where id= '$id'";
    $result8 = mysqli_query($db, $sql8);
}



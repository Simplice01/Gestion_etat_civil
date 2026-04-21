<?php
session_start();

if (isset($_SESSION['utilisateur_id'])) {
    header("Location: dashboard/index.php");
    exit;
} else {
    header("Location: auth/login.php");
    exit;
}

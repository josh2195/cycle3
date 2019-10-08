<?php
/**
 * Created by PhpStorm
 * User: jbedw
 * Date: 10/7/2019
 * Time: 3:21 PM
 */

session_start();
$currentfile = basename($_SERVER['PHP_SELF']);
//set the time
$rightnow = time();

//turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors','1');

//set the time zone
ini_set( 'date.timezone', 'America/New_York');
date_default_timezone_set('America/New_York');

require_once "connect.php";
require_once "functions.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Survey</title>
    <link rel="stylesheet" href="styles.css" />
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=5o7mj88vhvtv3r2c5v5qo4htc088gcb5l913qx5wlrtjn81y"></script>
    <script>tinymce.init({ selector:'textarea' });</script>

</head>
<body>
<header>
    <h1><?php echo $pagetitle; ?></h1>
    <nav><?php require_once "nav.php"; ?></nav>
</header>
<main>
    <p>
        <img src="images/ccu3.png" alt="CCU symbol" class="left">
        <img src="images/ccu2.jpg" alt="CCU symbol" class="right">
    </p>

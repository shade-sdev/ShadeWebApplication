<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/includes/classes/Constants.php");
ob_start();
session_start();

$host = Constants::$host;
$database = Constants::$database;
$username = Constants::$username;
$password = Constants::$password;


try {
    $con = new PDO("mysql:dbname=$database;host=$host", "$username", "$password");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}

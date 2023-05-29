<?php
require_once 'auth.php';
if ((!$userid = checkAuth()) || (!isset($_GET["q"]))) exit;

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$id = mysqli_real_escape_string($conn, $_GET["q"]);
$query = "DELETE FROM esercizi WHERE id = '$id' AND id_scheda IN (SELECT id FROM schede WHERE id_creatore = '$userid')";
$res = mysqli_query($conn, $query);

echo $res;

mysqli_close($conn);
?>
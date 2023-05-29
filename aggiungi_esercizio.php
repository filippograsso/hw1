<?php
require_once 'auth.php';
if ((!$userid = checkAuth()) || (!isset($_SESSION["idScheda"]))) exit;

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

if(isset($_POST['idEsercizio']) && isset($_POST['serie']) && isset($_POST['ripetizioni']))
$idEsercizio = mysqli_real_escape_string($conn, $_POST['idEsercizio']);
$serie = mysqli_real_escape_string($conn, $_POST['serie']);
$ripetizioni = mysqli_real_escape_string($conn, $_POST['ripetizioni']);
if($serie<1) $serie = 1;  if($serie>10) $serie = 10;
if($ripetizioni<1) $ripetizioni = 1;  if($ripetizioni>50) $ripetizioni = 50;

$query = "INSERT INTO esercizi (idOnline, id_scheda, n_serie, n_ripetizioni) VALUES('$idEsercizio', '".$_SESSION['idScheda']."', '$serie', '$ripetizioni')";

echo mysqli_query($conn, $query);

mysqli_close($conn);
?>

<?php
require_once 'auth.php';
if (!isset($_GET["scheda"])) exit;

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$query = "SELECT * FROM esercizi WHERE id_scheda='".$_GET["scheda"]."'";
$res = mysqli_query($conn, $query);

$array = array();
while($entry = mysqli_fetch_assoc($res)) {
    $array[] = array(
                'idOnline' => $entry['idOnline'],
                'serie' => $entry['n_serie'], 
                'ripetizioni' => $entry['n_ripetizioni'], 
                );
}

echo json_encode($array);

mysqli_close($conn);
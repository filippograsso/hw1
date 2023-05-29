<?php
require_once 'auth.php';
if ((!$userid = checkAuth())) exit;

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$query = "SELECT * FROM schede WHERE id_creatore='$userid'";
$res = mysqli_query($conn, $query);

$array = array();
while($entry = mysqli_fetch_assoc($res)) {
    $array[] = array('id' => $entry['id'],
                'nome' => $entry['nome'], 
                'tipo' => $entry['tipo'], 
                'categorie' => $entry['categorie'], 
                'giorno' => $entry['giorno_settimana'], 
                'pubblica' => $entry['pubblica']
                );
}

echo json_encode($array);

mysqli_close($conn);
?>

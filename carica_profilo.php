<?php
require_once 'auth.php';
if ((!$userid = checkAuth())) exit;

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$query = "SELECT * FROM users WHERE id='$userid'";
$res = mysqli_query($conn, $query);

if ($entry = mysqli_fetch_assoc($res)) 
    $array = array(
        'nome' => $entry['nome'],
        'cognome' => $entry['cognome'],
        'dataNascita' => $entry['dataNascita'],
        'genere' => $entry['genere'],
        'email' => $entry['email'],
        'username' => $entry['username'],
        'newsletter' => $entry['newsletter'],
        'propic' => $entry['propic'],
        'canzone' => $entry['canzone'],
    );
echo json_encode($array);

mysqli_close($conn);
?>
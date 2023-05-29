<?php
require_once 'auth.php';

$conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$query = "SELECT DISTINCT users.username, users.propic, schede.*
            FROM schede
            INNER JOIN users ON schede.id_creatore = users.id
            INNER JOIN (
                SELECT id_scheda, COUNT(*) AS count_esercizi
                FROM esercizi
                GROUP BY id_scheda
                HAVING count_esercizi >= 1
            ) AS esercizi_count ON schede.id = esercizi_count.id_scheda
            WHERE schede.pubblica = 1";
            $res = mysqli_query($conn, $query);

$array = array();
while($entry = mysqli_fetch_assoc($res)) {
    $array[] = array('id' => $entry['id'],
                'nome' => $entry['nome'], 
                'username' => $entry['username'],
                'tipo' => $entry['tipo'], 
                'categorie' => $entry['categorie'], 
                'giorno' => $entry['giorno_settimana'], 
                'pic' => $entry['propic']
                );
}

echo json_encode($array);

mysqli_close($conn);
?>

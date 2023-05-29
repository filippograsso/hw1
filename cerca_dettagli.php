<?php
require_once 'auth.php';
if (!isset($_GET["q"]))
    exit;

$url = 'https://wger.de/api/v2/exerciseinfo/';
$apiKey = '37af02dafae2abaeaf760b59c261d24c15869f4b';

$searchTerm = $_GET["q"];
$searchUrl = $url . urlencode($searchTerm) . '/';

// Inizializza la sessione cURL per la richiesta GET
$ch = curl_init($searchUrl);

// Imposta le opzioni di cURL per la richiesta GET
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Token ' . $apiKey
));

// Esegui la richiesta per ottenere i dati corrispondenti alla ricerca
$response = curl_exec($ch);

// Verifica se ci sono errori nella richiesta
if(curl_errno($ch))
    echo 'Errore nella richiesta cURL: ' . curl_error($ch);
else
    echo json_encode($response);

// Chiudi la sessione cURL
curl_close($ch);
?>

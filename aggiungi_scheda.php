<?php 
require_once 'auth.php';
if (!$userid = checkAuth()) exit;

if(!empty($_POST['nome'])){
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);

    $query = "SELECT * FROM schede WHERE id_creatore='".$_SESSION['user_id']."' and nome='$nome'";
    $res = mysqli_query($conn, $query);
    if (mysqli_num_rows($res) > 0) {
        echo "Hai già una scheda con questo nome";
    }

    else{
        $tipo = isset($_POST['tipo']) ? mysqli_real_escape_string($conn, $_POST['tipo']) : "DEFAULT";
        if ($tipo !== "DEFAULT") 
            $tipo = "'" . $tipo . "'";
        if (isset($_POST['categoria'])) {
            $categorie = implode(' ', $_POST['categoria']);
            mysqli_real_escape_string($conn, $categorie);
        }else $categorie = "DEFAULT";
        if ($categorie !== "DEFAULT") 
            $categorie = "'" . $categorie . "'";
        $giorno = isset($_POST['giorno']) ? mysqli_real_escape_string($conn, $_POST['giorno']) : "DEFAULT";
        if ($giorno !== "DEFAULT") 
            $giorno = "'" . $giorno . "'";
        if (isset($_POST['pubblica'])) $pubblica = mysqli_real_escape_string($conn, $_POST['pubblica']);
        else $pubblica = 0;

        $query = "INSERT INTO schede(id_creatore, nome, tipo, categorie, giorno_settimana, pubblica) VALUES ('".$_SESSION['user_id']."', '$nome', $tipo, $categorie, $giorno, '$pubblica')";
        if (mysqli_query($conn, $query)) {
            echo "Scheda creata";
        }else echo "Qualcosa è andato storto nella creazione, riprova";
    }

    mysqli_close($conn);
} else echo "La scheda deve avere un nome";

?>